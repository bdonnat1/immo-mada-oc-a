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
    name: "Produit",
    components: {
        Vuetable,
        VuetablePagination,
        VuetableFieldCheckbox,
        VuetableFieldMixin,

    },
    data: function () {

        return {
            pageTitle: "Liste des produits",
            base_url :this.BASE_URL,
            lien_image:this.BASE_URL+"/uploads/variation",
            files: [],
            images: [],
            total_data: 0,
            recherche: "",
            produit_variation: [],
            photos_produit: [],
            categorie: {},
            titlemodal: "Nouveau Produit",
            produit: { reference_produit: "", designation: "", categorie_id: "", statut: "" ,description_produit:"",superficie:0,longueur:0,largeur:0,position_map:''},
            statut: [
                { id: 1, value: "Actif" },
                { id: 2, value: "Inactif" },
            ],

            fichier: null,
            file: null,
            photos: [{
                id: 0,
                photos: '',

            }],
            variation_produit: [{
                id: 0,
                variation: '',
                prix: '',
                photos: ''
            }],
            vuetable: {
                moreParams: {},
                fields: [
                    {
                        name: "photos",
                        title: "Photos",
                        width: "60px",
                        dataClass:"text-center",
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "reference_produit",
                        title: "Reference",
                        sortField: 'reference_produit',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "longueur",
                        title: "Longueur",
                        sortField: 'longueur',
                        titleClass: 'text-uppercase text-bold',
                        dataClass: 'text-right'
                    },
                    {
                        name: "largeur",
                        title: "Largeur",
                        sortField: 'largeur',
                        titleClass: 'text-uppercase text-bold ',
                        dataClass: 'text-right'
                    },
                    {
                        name: "superficie",
                        title: "Superficie",
                        sortField: 'superficie',
                        titleClass: 'text-uppercase text-bold',
                        dataClass: 'text-right'
                    },
                    {
                        name: "position_map",
                        title: "Position MAP",
                        sortField: 'position_map',
                        titleClass: 'text-uppercase text-bold',
                        dataClass: 'text-right'
                    },
                    {
                        name: "designation",
                        title: "Designation",
                        sortField: 'designation',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "categorie",
                        title: "Categorie",
                        sortField: 'categorie',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "variation",
                        title: "Variation",
                        sortField: 'variation',
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
                    { field: 'reference_produit', direction: 'asc' }
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

            preview_link_variation:[{
                image:"",
            }],
            preview_link_photo:[],
            preview_link_produit:[],
        }

    },
    created() {

        this.get_categorie();
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

        onLoading() {
            console.log('loading... show your spinner here')
        },
        onLoaded() {
            console.log('loaded! .. hide your spinner here');
            this.pageTitle =  "Liste des produits (" + new Intl.NumberFormat().format(Number(this.$refs.vuetable.tablePagination.total)) + " lignes)";
        },

        OpenFile(index){
            $('#upload'+index).trigger("click");
        },
        openModal() {
            this.$bvModal.show("myCustomModal");
        },
        closeModal() {
            this.$bvModal.hide("myCustomModal");
            this.titlemodal;
            console.log('eto');
        },

        calculSuperficie(){
            this.produit.superficie = parseFloat(this.produit.longueur) * parseFloat(this.produit.largeur);
        },

        addProduit() {
            this.titlemodal = "Ajouter un poduit";
            this.images = [];
            this.preview_link_photo=[];
            this.produit = { reference_produit: "", designation: "", categorie_id: "", statut: "" ,description_produit:"",superficie:0,longueur:0,largeur:0,position_map:''},
            this.photos = [{
                id: 0,
                photos: ''
            }];
            this.variation_produit = [{
                    id: 0,
                    variation: '',
                    prix: '',
                    photos: ''
            }];
            this.preview_link_variation =[{
                image:"",
            }];
            this.type_submit = "insert";
            this.openModal();

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

        save() {
            var link = this.type_submit === 'insert' ? this.BASE_URL + '/produit/addaction' : this.BASE_URL + '/produit/editprodruit';

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

        get_categorie() {

            var link = this.BASE_URL + '/categorie/get';
            axios.get(link).then((response) => {
                console.log(this.categorie = response.data);
            })
        },
        getproduit: function (id) {
            this.preview_link_produit = [];
            axios.get(this.BASE_URL + "/produit/getid/" + id).then((response) => {

                this.produit = response.data.produit[0];
                this.variation_produit = response.data.variations;

                this.variation_produit.forEach(value=>{
                    this.preview_link_variation.push({
                        image:this.BASE_URL+"/assets/img/variation/"+value.photos,
                    })
                })
                this.photos = response.data.photos;

                this.photos.forEach(value => {
                    this.preview_link_photo.push({
                        image: this.BASE_URL+"/assets/img/produit/"+value.photos,
                        nom_image : value.photos,
                        id:value.id,
                    })
                    this.preview_link_produit.push({
                        nom_image: this.BASE_URL+"/assets/img/produit/"+value.photos,
                    })
                })
                this.openModal();
                this.titlemodal = "Modifier un produit";
                this.type_submit = "update";

            })

        },

        editRow(rowData){
            this.preview_link_variation = [];
            this.preview_link_photo = [];
            this.getproduit(rowData.id);
        },

        Editproduit(rowData) {

            this.getproduit(rowData.id);
            this.titlemodal = "Modifier un produit";
            this.type_submit = "update";
            this.openModal();

        },
        get_variation: function (id) {
            axios.get(this.BASE_URL + "/produit_variation/getid/" + id).then((response) => {

                this.variation_produit = response.data;
            })

        },
        Editvariation(rowData) {

            this.get_variation(rowData.id);
            this.titlemodal = "Modifier un produit";
            this.type_submit = "update";
            this.openModal();

        },
        get_photos: function (id) {
            axios.get(this.BASE_URL + "/produit_photos/getid/" + id).then((response) => {
                console.log(this.photos = response.data);
            })
        },
        Editphotos(rowData) {

            this.get_photos(rowData.id);
                this.titlemodal = "Modifier un produit";
            this.type_submit = "update";
            this.openModal();

        },
        getphotos(id) {
            axios.get(this.BASE_URL + "/produit_photos/get_photos/" + id).then((response) => {

                console.log(this.photos_produit = response.data);

            })
        },
        get_photos_produit(rowData) {
            var that = this;
            this.titlemodal = "Photos produit";
            that.getphotos(rowData.id);

        },
        getAllvariation(id) {
            axios.get(this.BASE_URL + "/produit_variation/get_varition/" + id).then((response) => {

                console.log(this.produit_variation = response.data);

            })
        },
        getvariation(rowData) {
            var that = this;
            this.titlemodal = "Variation";
            that.getAllvariation(rowData.id);

        },
        deleteproduit(rowData) {
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
                        axios.post(that.BASE_URL + "/produit/delete/" + rowData.id).then(function (response) {

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

        add() {
            this.photos.push({ libelle: '', photos: '' });
        },
        remove(index) {
            this.photos.splice(index, 1);

        },
        add_variation() {
            this.variation_produit.push({ variation: '', prix: '', photos: '',id:0 });
            this.preview_link_variation.push({
                image:"",
            });
        },


        remove_variation(index,variation) {
            this.variation_produit.splice(index, 1);
            this.preview_link_variation.splice(index, 1);
            console.log(index)
            if (this.type_submit ==='update'){
                axios.get(this.BASE_URL + "/produit_variation/deletevariation/" + variation.id).then( (response) =>{
                    console.log(response)
                });
            }
        },
        //  upload photos in variation
        createFile_variation(fichier,index) {

            var reader = new FileReader();
            var that = this;

            reader.onload = (e) => {
                that.preview_link = e.target.result;
                that.preview_link_variation[index].image = e.target.result;
            };

            reader.readAsDataURL(fichier);
        },
        uploadFile_variation(event, index) {

            const URL = this.BASE_URL + '/produit_variation/uploadfile';

            let data = new FormData();
            data.append('name', 'file-input');
            data.append('file-input', event.target.files[0]);
            console.log(event.target.files[0]);
            var files = event.target.files || event.dataTransfer.files;
            this.createFile_variation(files[0],index);

            let config = {
                header: {
                    'Content-Type': 'multipart/form-data'
                },

            }
            var that = this;
            axios.post(
                URL,
                data,
                config
            ).then(
                response => {
                    console.log('fichier upload response > ', response);
                    console.log(response)
                    that.variation_produit[index].photos = response.data.upload_data.file_name;
                }
            ).catch(error => {
                console.log(error);
            })
        },

        deleteImage(index,produit){
            this.preview_link_photo.splice(index,1);
            this.preview_link_produit.splice(index,1);
            if (this.type_submit ==='update'){
                console.log(produit)
                axios.get(this.BASE_URL + "/produit_photos/deleteproduit/" + produit.id).then( (response) =>{
                    console.log(response)
                });
            }
        },

        // upload photos
        createFile(fichier) {

            var reader = new FileReader();
            var that = this;
            console.log(fichier);
            reader.onload = (e) => {

                that.preview_link_produit.push({
                    nom_image :e.target.result
                });

                // that.preview_link_photo.push({
                //     image: e.target.result,
                //     nom_image : "",
                //     id:0,
                // })
            };
            reader.readAsDataURL(fichier);
        },

        async uploadFile(event) {

            const URL = this.BASE_URL + '/produit_photos/uploadfile';
            let files = event.target.files || event.dataTransfer.files;
            this.files = files;
            console.log(this.files.length);

            for (let i = 0; i < files.length; i++) {
                let data = new FormData();
                data.append('name', 'file-input');
                data.append('file-input', event.target.files[i]);
                var fichier = event.target.files || event.dataTransfer.files;
                console.log("Fichier", fichier)
                this.createFile(fichier[i]);

                let config = {
                    header: {
                        'Content-Type': 'multipart/form-data'
                    },

                }
                // var that = this;

                axios.post(
                    URL,
                    data,
                    config
                ).then(
                    response => {
                        console.log('fichier upload response > ', response);
                        // this.preview_link_photo[i].nom_image = response.data.upload_data.file_name;
                        this.preview_link_photo.push({
                            id:0,
                            nom_image:response.data.upload_data.file_name
                        })
                        // that.photos.photos= response.data.upload_data.file_name;
                    }
                ).catch(error => {
                    console.log(error);
                })

                // try {
                //     let response = await axios.post(
                //         URL,
                //         data,
                //         config
                //     );
                //     console.log('fichier upload response > ', response);
                //     that.photos.photos = response.data.upload_data.file_name;
                // } catch (error) {
                //     console.log(error);
                // }
            }



        },
        onChange() {
            console.log(this.files);
            this.files.forEach(img => {
                this.images.push(img)
                // this.preview_link_photo.push({
                //     image:this.BASE_URL+"/uploads/produit/"+img.name
                // })
            })


        },
        onReset() {
            this.images = []
        },

        produit_photos(pj) {
            let num = "";

            if (pj != null && pj != '') {
                let tabs = pj.split(",");
                tabs.forEach((element, index) => {
                    if (index > 0) {
                        num += "</br>";
                    }
                    num += element;
                });
            }

            return num;
        },



    }
}
