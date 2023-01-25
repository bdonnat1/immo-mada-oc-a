import axios from "axios";
import Vue from "vue";
import $ from "jquery";

export default {
    name: "Register",
    data: function() {
        return {
            pageTitle: "Liste des utilisateurs",

            crudData: {
                id: "",
                nom_client: "",
                prenom_client: "",
                mail_client: "",
                telephone_client: "",
                adresse_client: "",
                type_client: "Personnel",
                nif_client: "",
                stat_client: "",
                raison_sociale_client: "",
                login_client:''

            },
            type_submit:"insert",
        }
    },
    methods: {

        onSubmit() {
            var that = this;
            var link = that.type_submit == 'insert' ? that.BASE_URL + "/client/addaction" : that.BASE_URL + "/client/editaction";
            axios.post(link, $("#formulaire").serialize()).then(function(response) {
                console.log(response);
                //this.message_reponse = response.data.msg;
                if (response.data.cle === "ok") {

                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'success',
                        position: 'top-right'
                    });
                    that.crudData = {
                        id: "",
                        nom_client: "",
                        prenom_client: "",
                        mail_client: "",
                        telephone_client: "",
                        adresse_client: "",
                        type_client: "Personnel",
                        nif_client: "",
                        stat_client: "",
                        raison_sociale_client: "",
                        mdp_client_confirmation: "",
                        login_client: '',
                        mdp_client: "",
                    };
                } else {

                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'error',
                        position: 'top-right'
                    });
                }
            });
        },

    },
    watch: {

    },
    computed: {

    },
    created: function() {
        this.$emit('change-page');
    },
    mounted: function() {


    }
}
