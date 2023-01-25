<style scoped>
@import "~vue-toast-notification/dist/theme-sugar.css";
@import "../../public/assets/login/css/main.css";
@import "../../public/assets/login/css/util.css";
</style>
<template>
  <div class="main-wrap">
    <div class="limiter">
      <div class="container-login100">
        <div class="wrap-login100 p-b-20">
          <form id="formulaire" class="login100-form validate-form"  v-on:submit.prevent="onSubmit">
            <span class="login100-form-avatar">
              <img
                src="../../public/assets/img/logo-smcm.png"
                alt="Logo"
                width="100%"
                v-if="NOM_SOCIETE == 'SMCM' && LOGO != 'CHANTIER'"
              />
              <img
                src="../../public/assets/img/logo-chantierplus.jpg"
                alt="Logo"
                width="100%"
                v-if="NOM_SOCIETE == 'ECONS' && LOGO != 'CHANTIER'"
              />
              <img
                src="../../public/assets/img/logo-chantierplus.jpg"
                alt="Logo"
                width="100%"
                v-if="LOGO == 'CHANTIER'"
              />
              <!-- <span
                  href="#"
                  style="color: #363636; font-size: 4.8rem; letter-spacing: 2px"
                >
                  <span class="text-center">SMCM</span>
                </span> -->
            </span>

            <div
              class="wrap-input100 validate-input m-t-85 m-b-35"
              data-validate="Entrer votre login"
            >
              <input class="input100" type="text" name="login" />
              <span class="focus-input100" data-placeholder="Login"></span>
            </div>

            <div
              class="wrap-input100 validate-input m-b-50"
              data-validate="Entrer votre mot de passe"
            >
              <input class="input100" type="password" name="password" />
              <span
                class="focus-input100"
                data-placeholder="Mot de passe"
              ></span>
            </div>

            <div class="container-login100-form-btn">
              <button class="login100-form-btn">Se connecter</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="dropDownSelect1"></div>
  </div>
</template>

<script>
import Vue from 'vue'
import axios from 'axios'
import $ from 'jquery'
export default {
  name: "Login",
  methods: {
    onSubmit() {
      var link = Vue.BASE_URL+"/users/loginaction";
      //var that = this;
      axios.post(link, $("#formulaire").serialize()).then(function (response) {
        console.log(response);
        //this.message_reponse = response.data.msg;
        if (response.data.error === "") {
          console.log(response.data.message);
          
          Vue.$toast.open({
            message: response.data.message,
            type: "success",
            position: "top",
            // all of other options may go here
          });
          location.reload();
        } else {
          console.log("Erreur");
          Vue.$toast.open({
            message: response.data.error,
            type: "error",
            position: "top",
            // all of other options may go here
          });
        }
      });
    },
  },
  mounted: function () {},
};
</script>