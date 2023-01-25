<template>
    <div class="header-top">
        <div class="wrap">
            <div class="logo">
                <a href="index.html"><img :src="BASE_URL + '/assets/img/logo-ctht.png'" alt="" height="75px"/></a>
            </div>
            <div class="cssmenu">
                <ul>
                    <!-- <li class="active"><a href="#/login">Se connecter</a></li> -->
                    <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                        <router-link
                            to="/login"
                        >
                        Se connecter
                        </router-link>
                        <!-- <a href="#/login">Se connecter</a> -->
                    </li>
                    <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                        <!-- <a href="#/login">Mes commande</a> -->
                        <router-link
                            to="/login"
                        >
                        Mes commande
                        </router-link>
                    </li>
                    
                    <li>
                        <a href="javascript:void(0)" v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null">
                            <i class="fa fa-user"></i> {{userData.admin_ctht_nom}} (<span style="text-transform: capitalize !important;">{{userData.admin_ctht_role}}</span>)
                        </a>
                        <router-link
                            v-else
                            to="/admin"
                        >
                        <i class="fa fa-user"></i> Espace admin
                        </router-link>
                        <!-- <a href="#/admin"><i class="fa fa-user"></i> Espace admin</a> -->
                    </li>
                    <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null">
                        <a style="color: #db0000;text-transform: none !important;" href="javascript:void(0)" @click="doLogout" class="">Se deconnecter</a>
                    </li>
                </ul>
            </div>

            <ul class="icon2 sub-icon2 profile_img panier-contaiener">
                <li> 
                    <a class="active-icon c2 panierimg"> 
                        <img :src="BASE_URL + '/assets/images/shopping-cart.png'" alt="" height="30px"/> 
                    </a>
                    <ul class="sub-icon2 list" style="width: 500px!important; padding: 10px;">
                        <table class="table table-striped table-bordered panier" style="font-size: 12px">
                            <thead>
                                <tr class="text-uppercase text-bold">
                                    <th class="text-left">produit</th>
                                    <th class="text-left">variation</th>
                                    <th>qte</th>
                                    <th>PU</th>
                                    <th>Montant</th>
                                    <th>-</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p,index) in panier" :key="index+'999'">
                                    <td class="text-left">{{p.designation}}</td>
                                    <td>{{p.variation}}</td>
                                    <td > <input  style="width: 50px; border: none; text-align: right;" type="number" @input="calculMontant" min="1" v-model="p.qte"></td>
                                    <td class="text-right"> {{FormatNombre(p.prix)}} Ar</td>
                                    <td class="text-right"> {{FormatNombre(p.montant_total)}} Ar</td>

                                    <td style="cursor:pointer;" @click="deleteCookies(p)">
                                        <span class="fa fa-times text-danger" style="cursor: pointer"></span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="text-bold">
                                    <th colspan="4" class="text-right text-uppercase">Montant total</th>
                                    <th class="text-right">{{FormatNombre(total_general)}} Ar</th>
                                </tr>
                            </tfoot>
                        </table>
                        <a  href="#/panier/liste" class="btn btn-dark btn-sm rounded-0 text-black" type="button" >
                            <i class="fa fa-shopping-cart mr-1"></i> Voir le panier
                        </a>
                    </ul>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        props: {
            userData: { 
                type: Object,
                default: null,
                required: false
            },
        },
        name: "HeaderTop",
        data: function () {
            return{
                panier : [],
                session:[],
                total_general:0,
            }
        },
        methods:{
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

            calculMontant(){
                this.$cookies.remove("dna_panier");
                this.$cookies.set('dna_panier', JSON.stringify(this.panier));
                this.$parent.getCookies();
                this.total_general = this.$parent.CalculMontnt(this.panier)

            },
            deleteCookies(data){
                this.panier.forEach((value,index) => {
                    if (value.id_count == data.id_count){
                        console.log(value)
                        console.log(index)
                       this.panier.splice(index,1);
                    }
                })
                this.$cookies.remove("dna_panier");
                this.$cookies.set('dna_panier', JSON.stringify(this.panier));
                this.$parent.getCookies();
            },
            doLogout: function () {
                var that = this;
                axios.get(this.BASE_URL + "/utilisateur/logout").then(function () {
                    that.$router.push("/admin");
                });
            },
        },
        created() {

        },
        mounted() {

            this.panier= this.$parent.DataCookies;
            this.total_general = JSON.parse(this.$cookies.get('ctht_total_montant_panier'));
            this.userData.admin_ctht_nom = this.userData.admin_ctht_nom.replace("+", " ");
            this.userData.admin_ctht_role = this.userData.admin_ctht_role.replace("+", " ");
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
    }
</script>

<style scoped>
    table.panier tr th{
        border: none!important;
    }
</style>
