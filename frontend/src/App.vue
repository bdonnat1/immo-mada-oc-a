<style scoped>
@import '~bootstrap/dist/css/bootstrap.css';
@import '~bootstrap-vue/dist/bootstrap-vue.css';
</style>
<template id="main-app">
    <div>
        <HeaderTop :userData="userData" />
        <HeaderBottom :userData="userData" />
        <main>

            <router-view @change-page="onChangePage" />
        </main>
        <Footer />
    </div>
</template>

<script>
    // const axios = () => import ("axios");

    import axios from "axios"


    // Importer les Layouts
    import HeaderTop from "./layouts/HeaderTop";
    import HeaderBottom from "./layouts/HeaderBottom";
    import Footer from "./layouts/Footer";

    export default {
        components: {
            HeaderTop,
            HeaderBottom,
            // IndexBanner,
            Footer
        },
        data: function () {
            return {
                titre: "",
                showLoading: false,
                userData: {},
                base_url: this.BASE_URL,
                current_exercice: "",
                exercices: [],
                showHeader: true,
                Panier:[],
                DataCookies:[],
                total_Montant_general:0,
            };
        },
        watch:{
            total_Montant_general(new_value, ancien_value){
                console.log(new_value)
                console.log(ancien_value)
                console.log(this)
            }
        },
        methods: {
            /**
             *  fonction permet d'avoir les liste des cookies
             */
            getCookies(){
                var cookies =  JSON.parse(this.$cookies.get('dna_panier'));
                this.DataCookies= cookies == null ? [] : cookies;
                this.CalculMontnt(this.DataCookies);
            },

            /**
             * permet de calculer le montant total d'achat dans le panier
             * @param panier
             * @returns {number}
             * @constructor
             */
            CalculMontnt(panier){

                var montant_toal = 0;
                if (panier.length > 0){ // si existe cookies alor calcul montant général
                    var total = 0;
                    this.DataCookies.forEach(value => {
                        value.montant_total = parseInt(value.qte) * parseInt(value.prix);
                        total += parseInt(value.qte) * parseInt(value.prix);
                    })
                    this.total_Montant_general = total;
                    montant_toal = total;
                }else{
                    this.total_Montant_general = 0;
                    montant_toal = 0;
                }

                return montant_toal;
            },

            getPannier(){
                this.getCookies();
            },

            onChangePage: function (pageTitle) {
                this.titre = pageTitle;
                this.showHeader = true;
                console.log(this.titre);
            },

            onChangeLoad: function (status, userData) {
                // console.log("LOADING");
                if (status == "loading") {
                    this.showLoading = true;
                    console.log("LOADING");
                } else {
                    this.showLoading = false;
                    console.log("LOADED");
                }
                this.userData = userData;
                // console.log("utilisateur **************");
                // console.log(this.userData);
            },
            doLogout: function () {
                var that = this;
                axios.get(this.BASE_URL + "/utilisateur/logout").then(function () {
                    that.$router.push("/admin");
                });
            },

        },
        created: function () {

            this.getCookies();
            this.$parent.$on("change-load", this.onChangeLoad);
        },
        computed: {
            'userData.admin_ctht_nom': function() {
                if (this.userData.admin_ctht_nom == "" || this.userData.admin_ctht_nom == null) {
                    return "";
                }
                return this.userData.admin_ctht_nom.replace("+", " ");
            },
            'userData.admin_ctht_role': function() {
                if (this.userData.admin_ctht_role == "" || this.userData.admin_ctht_role == null) {
                    return "";
                }
                return this.userData.admin_ctht_role.replace("+", " ");
            }
        }
    };
</script>
<style scoped>
    @import "~@fortawesome/fontawesome-free/css/all.css";
    @import "~bootstrap/dist/css/bootstrap.css";
    @import "~bootstrap-vue/dist/bootstrap-vue.css";
    @import "~vue-toast-notification/dist/theme-sugar.css";
    @import "~pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
  </style>
