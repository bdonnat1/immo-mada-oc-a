import axios from "axios"
import Vue from "vue"
import $ from "jquery";
import Vuetable from 'vuetable-2'
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetableFieldCheckbox from 'vuetable-2/src/components/VuetableFieldCheckbox.vue';
import VuetableFieldMixin from 'vuetable-2/src/components/VuetableFieldMixin'
import vSelect from 'vue-select'


Vue.component('v-select', vSelect)

export default {
    name: "Utilisateur",
    components: {
        Vuetable,
        VuetablePagination,
        VuetableFieldCheckbox,
        VuetableFieldMixin,

    },
    data: function () {

        return {
            photos_utilisateur:null,
            total_data: 0,
            recherche: "",
            titlemodal: "Nouveau Utilisateur",

            vuetable: {
                moreParams: {},
                fields: [
                    {
                        name: "photos_utilisateur",
                        title: "Photos",
                        width: "60px",
                        dataClass:"text-center",
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "nom_utilisateur",
                        title: "Nom",
                        sortField: 'nom_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "login_utilisateur",
                        title: "Login",
                        sortField: 'login_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "telephone_utilisateur",
                        title: "Téléphone",
                        sortField: 'telephone_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "mail_utilisateur",
                        title: "Mail",
                        sortField: 'mail_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "role_utilisateur",
                        title: "Rôle",
                        sortField: 'role_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "statut_utilisateur",
                        title: "Statut",
                        sortField: 'statut_utilisateur',
                        titleClass: 'text-uppercase text-bold',
                    },

                    {
                        name: "-",
                        title: "-",
                        width:"80px",
                        titleClass:"text-center"
                    }

                ],
                sortOrder: [
                    { field: 'nom_utilisateur', direction: 'asc' }
                ],
                css: {
                    table: {
                        tableClass: 'table table-striped table-bordered table-hover table-sm ',
                        loadingClass: 'loading',
                        ascendingIcon: 'fas fa-chevron-up',
                        descendingIcon: 'fas fa-chevron-down',
                        handleIcon: 'fas fa-spinner',
                    },
                    pagination: {
                        infoClass: 'pull-left ',
                        wrapperClass: 'vuetable-pagination text-center',
                        activeClass: 'btn-default',
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

            crudData:{
                nom_utilisateur:'',
                mail_utilisateur:'',
                login_utilisateur:'',
                mdp_utilisateur:'',
                role_utilisateur:'',
                telephone_utilisateur:'',
                photos_utilisateur:'',
                statut_utilisateur:'',
            },
            role_user:[{text:"Administrateur"},{text: "Super Administrateur"}],
            statut_user:[{text:"Activer"},{text: "Désactiver"}],
            preview_link:this.BASE_URL+'/assets/img/default/personne.png',
        }

    },
    created() {


        this.$emit('change-page', "titre");
        this.recherche = "";
        this.criteriacol = "";
    },
    mounted: function() {
        
        if(this.$parent.userData.admin_ctht_id == null || this.$parent.userData.admin_ctht_id == "") {
            this.$router.push("/admin");
        }
    },
    methods: {
        uploadImage(event) {
            const URL = this.BASE_URL + '/produit_variation/uploadfile';

            let data = new FormData();
            data.append('name', 'file-input');
            data.append('file-input', event.target.files[0]);
            console.log(event.target.files[0]);
            var files = event.target.files || event.dataTransfer.files;
            this.createImage(files[0]);

            let config = {
                header: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: progressEvent => {
                    var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                    console.log(percentCompleted)
                }
            }
            var that = this;
            axios.post(
                URL,
                data,
                config
            ).then(
                response => {
                    console.log('image upload response > ', response);
                    that.crudData.photos_utilisateur = response.data.upload_data.file_name;

                }
            ).catch(error => {
                console.log(error);
            })
        },
        createImage(file) {
            var reader = new FileReader();
            reader.onload = (e) => {
                this.preview_link = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        AddData(){
            this.preview_link = this.BASE_URL+'/assets/img/default/personne.png';
            this.photos_utilisateur = null;
            this.titlemodal='Nouveau Utilisateur';
            this.crudData={
                nom_utilisateur:'',
                mail_utilisateur:'',
                login_utilisateur:'',
                mdp_utilisateur:'',
                telephone_utilisateur:'',
                role_utilisateur:'',
                statut_utilisateur:'',
                photos_utilisateur:''
            };
            this.type_submit = 'insert';
            this.openModal();
        },

        openModal() {
            this.$bvModal.show("crudModal");
        },
        closeModal() {
            this.$bvModal.hide("crudModal");
        },


        onPaginationData(paginationData) {
            var that = this;
            that.total_data = that.$refs.vuetable.tablePagination.total;
            this.$refs.pagination.setPaginationData(paginationData)
        },
        onChangePage(page) {
            this.$refs.vuetable.changePage(page)
        },
        setFilter() {
            this.vuetable.moreParams.filter = this.recherche;
            this.vuetable.moreParams.criteriacol = this.criteriacol;

            Vue.nextTick(() => this.$refs.vuetable.refresh());
        },

        onsubmit() {
            var link = this.type_submit === 'insert' ? this.BASE_URL + '/utilisateur/addaction' : this.BASE_URL + '/utilisateur/editaction';

            axios.post(link, $("#formulaire").serialize()).then((response) => {

                if (response.data.cle === "ok") {
                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'success',
                        position: 'top-right'
                    });

                    this.closeModal();
                    Vue.nextTick(() => this.$refs.vuetable.refresh());

                } else {
                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'error',
                        position: 'top-right'
                    });
                }
            })

        },

        editRow(rowData){
           console.log(rowData)
            axios.get(this.BASE_URL + "/utilisateur/get/" + rowData.id).then((response) => {
                this.crudData = response.data[0];
                this.titlemodal = "Modification";
                this.type_submit ="update";
                this.openModal();
                this.preview_link = this.crudData.photos_utilisateur != "" ? this.BASE_URL+'/assets/img/utilisateur/'+this.crudData.photos_utilisateur : this.BASE_URL+'/assets/img/default/personne.png';
            })
        },

        deleteRow(rowData) {
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
                    if (value == true) {
                        axios.post(that.BASE_URL + "/utilisateur/delete/" + rowData.id).then( (response)=> {

                            if (response.data.cle === "ok") {
                                Vue.$toast.open({
                                    message: response.data.msg,
                                    type: 'success',
                                    position: 'top-right'
                                });
                                Vue.nextTick(() => that.$refs.vuetable.refresh());
                            } else {

                                Vue.$toast.open({
                                    message: response.data.msg,
                                    type: 'error',
                                    position: 'top-right'

                                });
                            }

                        });
                    }
                })
                .catch(err => {
                    console.log(err);

                })

        },

    }
}
