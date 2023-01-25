import IndexBanner from "../../layouts/IndexBanner";
import axios from "axios";
import vSelect from 'vue-select'
import Vue from "vue";

Vue.component('v-select', vSelect)
export default {
    name: "Main",
    components: {IndexBanner},
    data: function () {
        return{
            tab_Produit:[],
            produits: [],
            critere: "",
            categorie_id: "",
            categories: [],
            tree_categorie:[],
            categorie_parent_id:'',
            tab_sous_categorie:[],
            sous_categorie_id:'',
        }
    },
    methods:{
        /**
         * fonction permet de charger les sous catégorie
         */
        chargeSousCategorie(){
            this.sous_categorie_id = '';
            axios.get(this.BASE_URL + "/categorie/getsouscategorie/"+this.categorie_parent_id).then((response) => {
                this.tab_sous_categorie = response.data.sous_categorie;
                this.categorie_id = this.categorie_parent_id;
                this.getALlProduit();
            })
        },

        gotosingle(id){
            this.$router.push("single/"+id);
        },

        //fonction pour charger liste des produit
        getALlProduit(){
            axios.get(this.BASE_URL + "/produit/getallproduit?search="+this.critere+"&categorie_id="+this.categorie_id+"&souscategorie_id="+this.sous_categorie_id).then((response) => {
                this.tab_Produit = response.data.produits;
                this.categories = response.data.categories;
            })
        },
        GotoSinglePage(produit){
            this.$router.push({ path: '/single/'+produit })
            this.$router.push({ name: 'Single' })
        }
    },
    created(){
        // this.getALlProduit();
    },
    watch: {
        // 'categorie_id': function() {
        //     this.getALlProduit();
        //     alert("ok");
        // },
        '$route.params.categorie_id': {
            handler: function(search) {
               console.log(search);
               this.categorie_id = search != "" && search != "undefined" ? search : "";
               this.getALlProduit();
            },
            deep: true,
            immediate: true
        }
    },
    mounted(){
        console.log(this.$route);
        if(this.$route.params.length > 0) {
            if(this.$route.params.categorie_id != "" && this.$route.params.categorie_id != "undefined") {
                this.categorie_id = this.$route.params.categorie_id;
            }
        }
        else {
            this.categorie_id = "";
        }
        this.getALlProduit();

    }
}
