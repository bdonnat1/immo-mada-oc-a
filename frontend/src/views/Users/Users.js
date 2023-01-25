import Vue from 'vue'
import Vuetable from 'vuetable-2'
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetableFieldCheckbox from 'vuetable-2/src/components/VuetableFieldCheckbox.vue';
import VuetableFieldMixin from 'vuetable-2/src/components/VuetableFieldMixin'
import $ from 'jquery'
import axios from "axios";
import JsonExcel from 'vue-json-excel'
import vSelect from 'vue-select'
import http from "../../tools/http-common"


Vue.component('downloadExcel', JsonExcel)
Vue.component('v-select', vSelect)


export default {
    components: {
        Vuetable,
        VuetablePagination,
        VuetableFieldCheckbox,
        VuetableFieldMixin
    },
    data: function() {
        return {
            pageTitle: "Liste des utilisateurs",

            vuetable: {
                moreParams: {},
                fields: [
                    // {
                    //     name: VuetableFieldCheckbox,
                    //     titleClass: 'text-center',
                    //     dataClass: 'text-center'
                    // },
                    {
                        name: 'nom',
                        title: 'Nom Complet',
                        sortField: 'nom_complet',
                        dataClass: 'text-left'
                    },
                    {
                        name: 'login',
                        title: 'Login',
                        sortField: 'login',
                        dataClass: 'text-left'
                    },
                    {
                        name: 'role',
                        title: 'Role',
                        sortField: 'role',
                        dataClass: 'text-left',
                        width: "100px",
                    },
                    {
                        name: 'statut',
                        title: 'Statut du compte',
                        sortField: 'statut',
                        dataClass: 'text-left',
                        width: "150px",
                    },

                    {
                        name: 'actions',
                        width: "80px",
                        dataClass: "text-center"
                    }

                ],
                sortOrder: [
                    { field: 'nom', direction: 'asc' }
                ],
                css: {
                    table: {
                        tableClass: 'table table-striped table-bordered table-hovered',
                        loadingClass: 'loading',
                        ascendingIcon: 'fas fa-arrow-up fa-sm',
                        descendingIcon: 'fas fa-arrow-down fa-sm',
                        handleIcon: 'fas fa-bars fa-sm',
                    },
                    pagination: {
                        infoClass: 'pull-left ',
                        wrapperClass: 'vuetable-pagination text-center',
                        activeClass: 'btn-secondary',
                        disabledClass: 'disabled',
                        pageClass: 'btn btn-border',
                        linkClass: 'btn btn-border',
                        icons: {
                            first: '',
                            prev: '',
                            next: '',
                            last: '',
                        },
                    }
                },
            },
            motCle: "",

            crudform: {
                id: "",
                nom: "",
                login: "",
                mdp: "",
                validation_mdp: "",
                role: "SIMPLE",
                statut: "ACTIVE",

            },
            crudmodaltitle: "Ajouter un utilisateur",
            listdata: {
                magasins: [],
                pointventes: []
            },
            error_password: "",

        }
    },
    methods: {
        onPaginationData(paginationData) {
            this.$refs.pagination.setPaginationData(paginationData)
        },
        onChangePage(page) {
            this.$refs.vuetable.changePage(page)
        },
        editRow(rowData) {
            //alert("You clicked edit on" + JSON.stringify(rowData))
            axios.get(this.BASE_URL + "/users/get/" + rowData.id).then(response => {


                // console.log(response.data[0]);

                this.crudform.id = response.data[0].id;
                this.crudform.nom = response.data[0].nom;
                this.crudform.login = response.data[0].login;
                this.crudform.mdp = "";
                this.crudform.validation_mdp = "";
                this.crudform.role = response.data[0].role;
                this.crudform.statut = response.data[0].statut;

            });
            this.crudmodaltitle = "Editer un utilisateur";
            this.openModal();
        },
        deleteRow(rowData) {
            //alert("You clicked delete on" + JSON.stringify(rowData));
            var that = this;
            this.$bvModal.msgBoxConfirm('Voulez-vous vraiment supprimer cette ligne?', {
                    title: 'Confirmation',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'Supprimer',
                    cancelTitle: 'Annuler',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    console.log(value);
                    if (value == true) {
                        axios.post(that.BASE_URL + "/users/delete/" + rowData.id).then(function(response) {
                            console.log(response);
                            //this.message_reponse = response.data.msg;
                            if (response.data.error === "") {
                                console.log(response.data.message);
                                that.setFilter();
                                Vue.$toast.open({
                                    message: response.data.message,
                                    type: 'success',
                                    position: 'top-right'
                                        // all of other options may go here
                                });
                            } else {
                                console.log("Erreur");
                                Vue.$toast.open({
                                    message: response.data.error,
                                    type: 'error',
                                    position: 'top-right'
                                        // all of other options may go here
                                });
                            }
                        });
                    }
                })
                .catch(err => {
                    console.log(err);
                    // An error occurred
                })

        },
        onLoading() {
            // console.log('loading... show your spinner here')
            this.$parent.showLoading = true;
        },
        onLoaded() {
            // console.log('loaded! .. hide your spinner here');
            this.$parent.showLoading = false;
        },
        setFilter() {
            this.vuetable.moreParams.filter = this.motCle;
            this.vuetable.moreParams.criteriacol = this.criteriacol;
            Vue.nextTick(() => this.$refs.vuetable.refresh());
        },
        resetFilter() {
            this.motCle = "";
            this.criteriacol = "";
            this.vuetable.moreParams.filter = this.motCle;
            Vue.nextTick(() => this.$refs.vuetable.refresh());
        },

        openModal() {
            this.$bvModal.show("crudmodal");
        },
        closeModal() {
            this.$bvModal.hide("crudmodal");
        },
        addRow() {
            this.crudmodaltitle = "Ajouter un utilisateur";
            this.crudform = {
                id: "",
                nom: "",
                login: "",
                mdp: "",
                validation_mdp: "",
                role: "ADMIN",
                statut: "ACTIVE",
            };
            // console.log(this.crudform);
            this.openModal();
        },
        onSubmit() {
            var that = this;
            this.$bvModal.msgBoxConfirm('Voulez-vous vraiment continuer l\'enregistrement?', {
                    title: 'Confirmation',
                    size: 'md',
                    buttonSize: 'sm',
                    okVariant: 'success',
                    okTitle: 'Continuer',
                    cancelTitle: 'Annuler',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    console.log(value);
                    if (value == true) {
                        var link = that.crudform.id == '' ? that.BASE_URL + "/users/addaction" : that.BASE_URL + "/users/editaction";
                        axios.post(link, $("#formulaire").serialize()).then(function(response) {
                            console.log(response);
                            //this.message_reponse = response.data.msg;
                            if (response.data.error === "") {
                                console.log("Enregistrement effectué avec succès!");
                                that.closeModal();
                                that.setFilter();
                                Vue.$toast.open({
                                    message: 'Enregistrement effectué avec succès!',
                                    type: 'success',
                                    position: 'top-right'
                                        // all of other options may go here
                                });
                            } else {
                                console.log("Erreur");
                                Vue.$toast.open({
                                    message: response.data.error,
                                    type: 'error',
                                    position: 'top-right'
                                        // all of other options may go here
                                });
                            }
                        });
                    }
                })
                .catch(err => {
                    console.log(err);
                    // An error occurred
                });
        },
        fetchData: function() {
            axios.get(this.BASE_URL + "/users/fetchdata").then(response => {
                this.listdata = response.data;
            });
        },
        isValidPassword: function() {
            if (this.crudform.mdp != this.crudform.validation_mdp) {
                this.error_password = this.$t('users.error_password');
            } else {
                this.error_password = "";
            }
        },
        tester(){
            http.post("/users/test_securite",{"login":"Djasnive"},).then(response => {
                console.log(response);
            });
            $.ajax({
                type: "GET",
                url: this.BASE_URL+"/users/test_securite",
                dataType: 'json',
                headers: {
                    "Authorization": "Basic RRTRTRTRTRTRTRT"
                },
                data: '{ "comment" }',
                success: function (){
                    alert('Thanks for your comment!');
                }
            });
        }
    },
    watch: {

    },
    computed: {
        /*httpOptions(){
            return {headers: {'Authorization': "my-token"}} //table props -> :http-options="httpOptions"
        },*/
    },
    created: function() {
        this.$emit('change-page', this.pageTitle);

        this.motCle = "";
        this.criteriacol = "";
        this.fetchData();
    },
    mounted: function() {


    }
}
