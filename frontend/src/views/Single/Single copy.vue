<template>
    <div class="single">
        <div class="wrap">
            <div class="rsidebar span_1_of_left">
                
                <section  class="sky-form">
                    
                    <h4>{{produit_meme_categorie[0].categorie}}</h4>
                    <div class="row row1 scroll-pane">
                        <div class="col col-8">
                            <ul>
                                <li v-for="(p,index_produit) in produit_meme_categorie" :key="index_produit+'khjh'">

                                    <a :href="'#/single/'+p.id">{{p.reference_produit}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </section>

            </div>

            <div class="cont span_2_of_3">
                <div class="labout span_1_of_a1">
                    <!-- start product_slider -->
                    <ul id="etalage">
                        <li>
                            <img class="etalage_thumb_image" :src=" (page_charger) ? BASE_URL+'/assets/img/variation/'+variation.photos : BASE_URL+'/assets/img/default/camera1.png'"/>
                        </li>
                    </ul>


                    <!-- end product_slider -->
                </div>
                <div class="cont1 span_2_of_a1">
                    <h3 class="m_3">Lorem ipsum dolor sit amet</h3>
                    <h3 class="m_3">{{variation.variation}}</h3>

                    <div class="price_single">
                        <span class="actual">{{FormatNombre(variation.prix)}} (Ar)</span>
                    </div>

                    <ul class="options">
                        <h4 class="m_9">Select a Size</h4>
                        <li v-for="(v,index_v) in tab_Produit.value_variation" :key="index_v+'888'">
                            <a  @click="choiceVariation(v)">{{v.variation}}</a>
                        </li>
                        <div class="clear"></div>
                    </ul>
                    <div class="btn_form">
                        <button @click="addPanier" class="add-to-card" type="button"><i class="fa fa-shopping-cart mr-1"></i> PANIER</button>
                       <!-- <form>
                           <input type="submit" value="Panier" title="">
                       </form> -->
                    </div>
                   <ul class="add-to-links">
                       <li><img :src="BASE_URL+'/public/assets/images/wish.png'" alt=""/><a href="#">Add to wishlist</a></li>
                   </ul>
                   <p class="m_desc">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                       incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                       ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                       voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                   <div class="social_single">
                       <ul>
                           <li class="fb"><a href="#"><span> </span></a></li>
                           <li class="tw"><a href="#"><span> </span></a></li>
                           <li class="g_plus"><a href="#"><span> </span></a></li>
                           <li class="rss"><a href="#"><span> </span></a></li>
                       </ul>
                   </div>
                </div>
                <div class="clear"></div>


                <ul id="flexiselDemo3">
                    <li v-for="(produit,index_produit) in tab_Produit.produit_photos" :key="index_produit+'kkk'">
                        <img :src="BASE_URL+'/assets/img/produit/'+produit.photos"/>
                       <div class="grid-flex"><a href="#">Bloch</a>
                           <p>Rs 850</p></div>
                    </li>

                   <li>
                       <img :src="BASE_URL+'/assets/img/produit/1.jpg'"/>
                       <div class="grid-flex"><a href="#">Bloch</a>
                           <p>Rs 850</p></div>
                   </li>
                   <li><img :src="BASE_URL+'/assets/img/produit/2.jpg'"/>
                       <div class="grid-flex"><a href="#">Capzio</a>
                           <p>Rs 850</p></div>
                   </li>
                   <li><img :src="BASE_URL+'/assets/img/produit/3.jpg'"/>
                       <div class="grid-flex"><a href="#">Zumba</a>
                           <p>Rs 850</p></div>
                   </li>
                   <li><img :src="BASE_URL+'/assets/img/produit/4.jpg'"/>
                       <div class="grid-flex"><a href="#">Bloch</a>
                           <p>Rs 850</p></div>
                   </li>
                   <li><img :src="BASE_URL+'/assets/img/produit/5.jpg'"/>
                       <div class="grid-flex"><a href="#">Capzio</a>
                           <p>Rs 850</p></div>
                   </li>
                </ul>

               <div class="toogle">
                   <h3 class="m_3">Product Details</h3>
                   <p class="m_text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                       euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis
                       nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis
                       autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
                       eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent
                       luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta
                       nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</p>
               </div>


               <div class="toogle">
                   <h3 class="m_3">Product Reviews</h3>
                   <p class="m_text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                       euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis
                       nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis
                       autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
                       eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent
                       luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta
                       nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</p>
               </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
    // import axios from "axios";
    import axios from "axios";

    export default {
        name: "Single",
        data: function () {
            return{
                tab_Produit:[],
                id_produit:this.$route.params.id_produit,
                page_charger : false,
                produit_meme_categorie:[],
                variation:{
                    id:"",
                    prix:0,
                    variation:"",
                    photos:this.BASE_URL+"camera1.png"
                },
                tab_panier:[],
                session:this.$parent.DataCookies,
            }
        },
        methods:{
            /**
             * fonction permet d'ajouter un panier
             */

            addPanier(){
                var compte = 0;
                this.$cookies.remove('ctht_total_montant_panier');

                axios.get(this.BASE_URL + "/produit_variation/getvariation/"+this.variation.id).then((response) => {
                    compte = (this.session.length ==0) ? 0 : parseInt(this.session.length ) + 1;
                    this.session.push({
                        id_count:compte,
                        id:response.data[0].id,
                        id_produit: response.data[0].produit_id,
                        reference_produit: response.data[0].reference_produit,
                        designation: response.data[0].designation,
                        variation: response.data[0].variation,
                        prix: response.data[0].prix,
                        qte:1,
                        montant_total : response.data[0].prix * 1,
                        nom_categorie: response.data[0].nom_categorie,
                        image_variation:response.data[0].photos
                    });
                    this.$cookies.set('dna_panier', JSON.stringify(this.session));

                    setTimeout(()=>{
                        var montant_general = 0;
                        this.session.forEach(value=>{
                            montant_general += parseInt(value.qte) * parseInt(value.prix);
                        })
                        this.$cookies.set('ctht_total_montant_panier', JSON.stringify(montant_general));

                        this.$parent.GetCookiesMontantTotal();
                    },500)
                })
            },

            /**
             * fonction pour choisir le type de variation à afficher
             * @param variation
             */
            choiceVariation(variation){
                axios.get(this.BASE_URL + "/produit_variation/getvariation/"+variation.id).then((response) => {
                    this.variation = response.data[0];
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
                for (var i = a.length-1; i >= 0; i--) {
                    c = (d != 0 && d % 3 == 0) ? a[i] + b + c : a[i] + c;
                    d++;
                }
                return c;
            },

            getALlProduit(id_produit){
                this.page_charger = false;
                axios.get(this.BASE_URL + "/produit/getelementproduit/"+id_produit).then((response) => {
                    this.page_charger = true;
                    this.tab_Produit = response.data;
                    this.variation = response.data.value_variation[0];
                    this.produit_meme_categorie = response.data.produit_meme_categorie;
                })
            },
        },
        created() {
            console.log(this.$parent)
            this.getALlProduit(this.$route.params.id_produit);
        },
        mounted() {

            setTimeout(()=>{
                window.$("#flexiselDemo1").flexisel();
                window.$("#flexiselDemo2").flexisel({
                    enableResponsiveBreakpoints: true,
                    responsiveBreakpoints: {
                        portrait: {
                            changePoint: 480,
                            visibleItems: 1
                        },
                        landscape: {
                            changePoint: 640,
                            visibleItems: 2
                        },
                        tablet: {
                            changePoint: 768,
                            visibleItems: 3
                        }
                    }
                });

                window.$("#flexiselDemo3").flexisel({
                    visibleItems: 5,
                    animationSpeed: 1000,
                    autoPlay: true,
                    autoPlaySpeed: 3000,
                    pauseOnHover: true,
                    enableResponsiveBreakpoints: true,
                    responsiveBreakpoints: {
                        portrait: {
                            changePoint: 480,
                            visibleItems: 1
                        },
                        landscape: {
                            changePoint: 640,
                            visibleItems: 2
                        },
                        tablet: {
                            changePoint: 768,
                            visibleItems: 3
                        }
                    }
                });
            },750)

        }
    }
</script>

<style scoped>

</style>
