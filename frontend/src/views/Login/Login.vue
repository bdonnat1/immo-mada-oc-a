<template>
    <div class="login">
        <div class="wrap">
            <div class="col_1_of_login span_1_of_login">
                <h4 class="title">Vous êtes nouveau sur le site?</h4>
                <h5 class="sub_title">
                    <router-link
                        :to="BASE_URL + '/register'"
                    >
                    Créer un compte
                    </router-link>
                    
                </h5>
                <p>Créer un compte pour passer votre commande et faire le suivi de vos achats.</p>
                <!-- <div class="button1">
                    <a href="register.html"><input type="submit" name="Submit" value="Continue"></a>
                </div> -->
                <div class="clear"></div>
            </div>
            <div class="col_1_of_login span_1_of_login">
                <div class="login-title">
                    <h4 class="title">Se connecter sur mon compte</h4>
                    <div class="comments-area">
                        <form id="formulaire" class="login100-form validate-form"  v-on:submit.prevent="onSubmit">
                            <p>
                                <label for="login_utilisateur">Login</label>
                                <span>*</span>
                                <input id="login_utilisateur" v-model="crudLogin.login_utilisateur" type="text" name="login_utilisateur">
                            </p>
                            <p>
                                <label for="mdp_utilisateur">Mot de passe</label>
                                <span>*</span>
                                <input id="mdp_utilisateur" v-model="crudLogin.mdp_utilisateur" type="password"  name="mdp_utilisateur">
                            </p>
                            <p id="login-form-remember">
                                <label><a href="#/register">Créer un compte</a></label>
                            </p>
                            <p>
                                <input type="submit" value="Se connecter">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import $ from 'jquery'

    export default {
        name: "Login",
        data(){
            return{
                crudLogin:{
                    login_utilisateur:"",
                    mdp:""
                }
            }
        },
        methods:{
            onSubmit() {
                var link = Vue.BASE_URL+"/client/loginaction";
                // var that = this;
                axios.post(link, $("#formulaire").serialize()).then(function (response) {
                    console.log(response);
                    //this.message_reponse = response.data.msg;
                    if (response.data.error === "") {
                        console.log(response.data.message);

                        Vue.$toast.open({
                            message: response.data.message,
                            type: "success",
                            position: "top-right",
                            // all of other options may go here
                        });

                    } else {
                        console.log("Erreur");
                        Vue.$toast.open({
                            message: response.data.error,
                            type: "error",
                            position: "top-right",
                            // all of other options may go here
                        });
                    }
                });
            },
        },
        created() {

        }
    }
</script>

