<style scoped>
/* @import "/assets/css/etalage.css"; */
/* @import "/assets/css/form.css"; */
</style>
<template>
    <div class="main">
        <div class="wrap">
            <div class="content-top">
                <form method="post" id="formulaire" v-on:submit.prevent="onsubmit">
                    <div class="col-md-12">
                        <section  class="sky-form">
                            <h4>Mon panier</h4>
                        </section>
                        <p>Pour valider votre commande, cliquez sur le bouton « passer la commande »</p>
                        <table class="table table-striped table-bordered bg-white">
                            <thead>
                            <tr class="text-uppercase text-bold">
                                <th width="100px">PHOTO</th>
                                <!-- <th width="150px">Référence</th> -->
                                <th>désignation</th>
                                <th>Variation</th>
                                <th width="80px">Qte</th>
                                <th class="text-center" width="120px">PU</th>
                                <th class="text-center" width="120px">Montant</th>
                                <th width="40px" class="text-center">-</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p,index) in panier" :key="index+'----'">
                                    <td>
                                        <img width="100px" :src="BASE_URL+'/assets/img/variation/'+p.image_variation" alt="">
                                    </td>

                                    <!-- <td>
                                        {{p.reference_produit}}
                                    </td> -->
                                    <td>
                                        {{p.designation}}
                                    </td>
                                    <td>{{p.variation}}</td>
                                    <td>

                                        <input type="text" class="d-none" name="id_variation[]" v-model="p.id">
                                        <input type="number"  @input="calculMontant" style="border: none!important;" name="qte[]" class="form-control text-right form-control-sm" v-model="p.qte">
                                    </td>
                                    <td class="text-right">
                                        {{FormatNombre(p.prix)}} Ar
                                    </td>
                                    <td class="text-right">
                                        {{FormatNombre(p.montant_total)}} Ar
                                    </td>
                                    <td>
                                        <button @click="deleteCookies(p)" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right" style="font-weight: bold">Total</th>
                                    <th class="text-right text-bold" style="font-weight: bold">{{FormatNombre(total_general)}} Ar</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <input type="text" class="d-none" name="id_client" v-model="id_client">
                        <input type="submit" class="btn float-right btn-dark rounded-0" value="Passer la commande">
<!--                        <button @click="envoyerCommande" class="btn float-right btn-dark rounded-0" type="button"> Commander</button>-->
                    </div>
                        <a class="d-none" id="lien" href="#/login"> Google</a>
                </form>

                <div class="clear"></div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from "axios";
    import $ from "jquery";
    import Vue from "vue";

    export default {
        name: "Panier",
        data(){
            return{
                panier:this.$parent.DataCookies,
                qte_total:0,
                montant_total:0,
                id_client : this.$cookies.get('client_ctht_id'),
                total_general: this.$cookies.total_Montant_general,
            }
        },
        created() {
            this.calculMontant();
        },
        methods:{

            calculMontant(){
                var total_general =0;
                this.panier.forEach(value => {
                    value.montant_total = parseInt(value.qte) * parseInt(value.prix);
                    total_general += parseInt(value.qte) * parseInt(value.prix);
                })
                this.total_general = total_general;
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

            onsubmit(){
                var verificaiton = this.$cookies.get('client_ctht_id');
                var bool =true;
                if (verificaiton == null){ // on peut faire la commande si l'utilsateur existe
                    var href = $('#lien').attr('href');
                    window.location.href = href;
                    bool = false;
                }

                if (bool){

                    var link = this.BASE_URL + '/boncommande/addaction';

                    axios.post(link, $("#formulaire").serialize()).then((response) => {
                        if (response.data.cle === "ok") {
                            Vue.$toast.open({
                                message: response.data.msg,
                                type: 'success',
                                position: 'top-right'
                            });

                            this.deleteAllCookies();
                            setTimeout(()=>{
                                location.reload();
                            },500)
                        } else {
                            Vue.$toast.open({
                                message: response.data.msg,
                                type: 'error',
                                position: 'top-right'
                            });
                        }
                    })

                }
            },

            /**
             * fonction pour supprimer tous les cookies
             */
            deleteAllCookies(){
                this.panier=[];
                this.$cookies.remove("dna_panier");
                this.$cookies.remove("ctht_total_montant_panier");
                this.$parent.getCookies();

            },

            /**
             * suppression élément suivant Data
             * @param data
             */
            deleteCookies(data) {
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
        }
    }
</script>
