import $ from "jquery";
import axios from "axios";
import Vue from "vue";
import VuePhotoZoomPro from 'vue-photo-zoom-pro'
import VueSlickCarousel from 'vue-slick-carousel'
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.component('vue-photo-zoom-pro', VuePhotoZoomPro);
Vue.use(VueGoogleMaps, {
    load: {
        // key: 'AIzaSyBzlLYISGjL_ovJwAehh6ydhB56fCCpPQw',
        // v: 'OPTIONAL VERSION NUMBER',
        libraries:'places',
    },
    installComponents: true
});

export default {
    name: "Single",
    components: {
        // VuePhotoZoomPro,
        VueSlickCarousel,

    },
    data: function () {
        return {

            center: {lat: 0, lng:0},
            markers: [{
                position: {lat: 0, lng: 0}
            }],

            tab_Produit: [],
            id_produit: this.$route.params.id_produit,
            page_charger: false,
            produit_meme_categorie: [],
            variation: {
                id: "",
                prix: 0,
                variation: "",
                photos: this.BASE_URL + "camera1.png"
            },
            produits: [],
            categories: [],
            tab_panier: [],
            produit_photos: [],
            session: this.$parent.DataCookies,
            current_photo_url: "",
            produit_photo: [],
            slider_settings: {
                "dots": true,
                "focusOnSelect": true,
                "infinite": true,
                "speed": 500,
                "slidesToShow": 3,
                "slidesToScroll": 3,
                "touchThreshold": 5
            },
            show_map: true,
            formData_sendMessage:{
                id:0,
                nom:'',
                adresse_mail:'',
                telephone:'',
                msg_envoyer:'',
                lien:location.href,
            }
        }
    },
    methods: {

        onSubmit:function(){
            var that = this;
            var link = this.BASE_URL+'/sendmail/addaction';

            axios.post(link,$("#formulaire").serialize()).then( (response)=> {
                if (response.data.cle==="ok"){
                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'success',
                        position: 'top-right'
                    });
                    that.closeModal();
                    Vue.nextTick(() => that.$refs.vuetable.refresh());

                }else{
                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'error',
                        position: 'top-right'
                    });
                }
            })
        },

        /**
         * fonction permet d'ajouter un panier
         */

        addPanier() {
            var compte = 0;
            this.$cookies.remove('ctht_total_montant_panier');

            axios.get(this.BASE_URL + "/produit_variation/getvariation/" + this.variation.id).then((response) => {
                compte = (this.session.length == 0) ? 0 : parseInt(this.session.length) + 1;
                this.session.push({
                    id_count: compte,
                    id: response.data[0].id,
                    id_produit: response.data[0].produit_id,
                    reference_produit: response.data[0].reference_produit,
                    designation: response.data[0].designation,
                    variation: response.data[0].variation,
                    prix: response.data[0].prix,
                    qte: 1,
                    montant_total: response.data[0].prix * 1,
                    nom_categorie: response.data[0].nom_categorie,
                    image_variation: response.data[0].photos
                });
                this.$cookies.set('dna_panier', JSON.stringify(this.session));

                Vue.$toast.open({
                    message: "Article ajouté dans le panier!",
                    type: 'info',
                    position: 'top-right'
                });

                setTimeout(() => {
                    var montant_general = 0;
                    this.session.forEach(value => {
                        montant_general += parseInt(value.qte) * parseInt(value.prix);
                    })
                    this.$cookies.set('ctht_total_montant_panier', JSON.stringify(montant_general));

                    this.$parent.GetCookiesMontantTotal();
                }, 500)
            })
        },

        /**
         * fonction pour choisir le type de variation à afficher
         * @param variation
         */
        choiceVariation(variation) {
            axios.get(this.BASE_URL + "/produit_variation/getvariation/" + variation.id).then((response) => {
                this.variation = response.data[0];

                if (this.variation.photos != "") {
                    this.current_photo_url = this.BASE_URL + '/assets/img/variation/' + this.variation.photos;
                }

            })
        },
        /**
         * fonction pour formater le nombre
         * @param a
         * @param b
         * @returns {string}
         * @constructor
         */
        FormatNombre(a, b) {
            a = '' + a;
            b = b || ' ';
            var c = '',
                d = 0;
            while (a.match(/^0[0-9]/)) {
                a = a.substr(1);
            }
            for (var i = a.length - 1; i >= 0; i--) {
                c = (d != 0 && d % 3 == 0) ? a[i] + b + c : a[i] + c;
                d++;
            }
            return c;
        },

        getALlProduit(id_produit) {
            this.page_charger = false;
            // let that = this;
            axios.get(this.BASE_URL + "/produit/getelementproduit/" + id_produit).then((response) => {
                this.page_charger = true;
                this.tab_Produit = response.data;
                this.variation = response.data.value_variation[0];
                this.produit_meme_categorie = response.data.produit_meme_categorie;
                this.produits = response.data.produits;
                this.categories = response.data.categories;
                this.produit_photos = response.data.produit_photos;


                this.show_map = (this.produits.position_map != null && this.produits.position_map !='') ? true : false;
                if (this.show_map){
                    var positionnement = this.produits.position_map.split(',');
                    this.center={
                        lat : parseFloat(positionnement[0]),
                        lng: parseFloat(positionnement[1])
                    } ;
                    this.markers[0].position = {
                        lat : parseFloat(positionnement[0]),
                        lng: parseFloat(positionnement[1])
                    };
                }

                if (this.produit_photos.length > 0) {
                    this.current_photo_url = this.BASE_URL + '/assets/img/produit/' + this.produit_photos[0].photos;
                } else {
                    this.current_photo_url = this.BASE_URL + '/assets/img/produit/nophoto.png';
                }

            })
        },
        changeImage(photo) {
            this.current_photo_url = this.BASE_URL + '/assets/img/produit/' + photo.photos;
        }
    },
    created() {
        // console.log(this.$parent)
    },
    mounted() {


        this.getALlProduit(this.$route.params.id_produit);
        console.log("xxx");


    }
}
