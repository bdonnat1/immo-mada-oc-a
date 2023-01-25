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
   name:"Categorie",
   components:{
        Vuetable,
        VuetablePagination,
        VuetableFieldCheckbox,
        VuetableFieldMixin
   },
    data:function(){
        return{
            preview_link:this.BASE_URL+'/assets/img/produit/nophoto.png',
            pageTitle:"",
            file:null,
            total_data:0,
            photos_categorie:[],
            recherche:"",
            titlemodal: "Nouveau Categorie",
            categorie:{nom_categorie:"",description:"",photos:''},
            vuetable :{
                moreParams: {},
                fields:[
                    {
                        name:"photos",
                        title:"Photos",
                        width:"80px",
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name:"nom_categorie",
                        title:"Categorie",
                        sortField: 'nom_categorie',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name:"description",
                        title:"Description",
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name:"nombreproduit",
                        title:"Produits",
                        sortField: 'nombreproduit',
                        titleClass: 'text-uppercase text-bold',
                        dataClass:"text-center",
                        width: "120px"
                    },
                    {
                        name:"action",
                        title:"-",
                        titleClass:"text-center",
                        width: "90px"
                    },

                ],
                sortOrder: [
                    { field: 'nom_categorie', direction: 'asc' }
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

        }

    },

    created(){

        this.$emit('change-page', "titre");
        this.recherche = "";
        this.criteriacol = "";

    },
    mounted: function() {
        
        if(this.$parent.userData.admin_ctht_id == null || this.$parent.userData.admin_ctht_id == "") {
            this.$router.push("/admin");
        }
    },
    methods:{
        
        onLoading() {
            console.log('loading... show your spinner here')
        },
        onLoaded() {
            console.log('loaded! .. hide your spinner here');
            this.pageTitle =  "Liste des catégories (" + new Intl.NumberFormat().format(Number(this.$refs.vuetable.tablePagination.total)) + " lignes)";
        },

        openModal() {
            this.$bvModal.show("modal");
        },
        closeModal() {
            this.$bvModal.hide("modal");
            this.titlemodal;
            console.log('eto');
        },

        addCategorie() {
            this.titlemodal = "Nouveau Categorie";
            this.categorie = { nom_categorie: '', description: '',photos:''};
            this.type_submit = "insert";
            this.preview_link = this.BASE_URL+'/assets/img/produit/nophoto.png';
            this.openModal();

        },
        onPaginationData(paginationData){
            var that = this;
            that.total_data = that.$refs.vuetable.tablePagination.total;
            this.$refs.pagination.setPaginationData(paginationData)
        },
          onChangePage(page){
            this.$refs.vuetable.changePage(page)
        },
        setFilter() {
            this.vuetable.moreParams.filter = this.recherche;
            this.vuetable.moreParams.criteriacol = this.criteriacol;

            Vue.nextTick(() => this.$refs.vuetable.refresh());
        },
        save()
        {
            var link = this.type_submit === 'insert' ? this.BASE_URL + '/categorie/addaction' : this.BASE_URL + '/categorie/editcategorie';

            axios.post(link, $("#formulaire").serialize()).then((response) => {

                if (response.data.cle === "ok") {
                    Vue.$toast.open({
                        message: response.data.msg[0],
                        type: 'success',
                        position: 'top-right'
                    });
                    this.closeModal();
                    Vue.nextTick(() => this.$refs.vuetable.refresh());

                } else {
                    Vue.$toast.open({
                        message: response.data.msg[0],
                        type: 'error',
                        position: 'top-right'
                    });
                }
            })

        },
        getphotos(id) {
            axios.get(this.BASE_URL + "/categorie/get_photos/" + id).then((response) => {

                console.log(this.photos_categorie= response.data);

            })
        },
        get_photos_categorie(rowData)
        {
         var that = this;
         this.titlemodal= "Photos Categorie";
         that.getphotos(rowData.id);

        },

        createFile(fichier) {

            var reader = new FileReader();
            var that = this;

            reader.onload = (e) => {
                that.preview_link = e.target.result;
            };
            reader.readAsDataURL(fichier);
     },
        uploadFile(event) {

            const URL = this.BASE_URL + '/categorie/uploadfile';

            let data = new FormData();
            data.append('name', 'file-input');
            data.append('file-input', event.target.files[0]);
            console.log(event.target.files[0]);
            var files = event.target.files || event.dataTransfer.files;
            this.createFile(files[0]);

            let config = {
                header: {
                    'Content-Type': 'multipart/form-data'
                },

            }
            var that = this;
            console.log(that.files);
            axios.post(
                URL,
                data,
                config
            ).then(
                response => {
                    console.log('fichier upload response > ', response);
                    that.categorie.photos= response.data.upload_data.file_name;
                }
            ).catch(error => {
                console.log(error);
            })
        },
        getcategorie:function(id)
        {
            axios.get(this.BASE_URL + "/categorie/getid/" + id).then((response) => {
                this.categorie = response.data[0];
                this.preview_link = this.categorie.photos =='' ? this.BASE_URL+'/assets/img/default/camera.png' : this.BASE_URL+'/assets/img/categorie/'+this.categorie.photos;

        })

       },


        Editcategorie(rowData) {

                this.getcategorie(rowData.id);
                this.titlemodal = "Modification Categorie";
                this.type_submit = "update";
                this.openModal();

        },


        deletecategorie(rowData) {
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
                        axios.post(that.BASE_URL + "/categorie/delete/" + rowData.id).then(function (response) {

                            if (response.data.cle === "ok") {
                                that.setFilter();
                                Vue.$toast.open({
                                    message: response.data.msg[0],
                                    type: 'success',
                                    position: 'top-right'

                                });
                            } else {

                                Vue.$toast.open({
                                    message: response.data.msg[0],
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
