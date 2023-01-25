import axios from "axios"
import Vue from "vue"
// import $ from "jquery";
import Vuetable from 'vuetable-2'
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetableFieldCheckbox from 'vuetable-2/src/components/VuetableFieldCheckbox.vue';
import VuetableFieldMixin from 'vuetable-2/src/components/VuetableFieldMixin'
import vSelect from 'vue-select'
import moment from 'moment';


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
            pageTitle: "",
            base_url :this.BASE_URL,
            total_data: 0,
            recherche: "",
            choix_etat:"",
            filtreDate: {
                dateDebut: moment().format('DD/MM/YYYY'),
                dateFin: moment().format('DD/MM/YYYY')
            },
            dateOptions: {
                format: 'DD/MM/YYYY',
            },

            vuetable: {
                moreParams: {},
                fields: [
                    {
                        name: "date_bon_de_commande_formatted",
                        title: "Date",
                        sortField: "date_bon_de_commande_formatted",
                        titleClass: 'text-uppercase text-bold text-center',
                        dataClass: 'text-center',
                        width: '100px',
                    },
                    {
                        name: "numero_facture",
                        title: "N° Com",
                        sortField: 'numero_facture',
                        titleClass: 'text-uppercase text-bold',
                        dataClass: 'text-center',
                    },
                    {
                        name: "statut_facture",
                        title: "Statut",
                        sortField: "statut_facture",
                        titleClass: 'text-uppercase text-bold text-center',
                        dataClass: 'text-center',
                    },
                    {
                        name: "nom_prenom",
                        title: "Client",
                        sortField: 'nom_prenom',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "mail_client",
                        title: "Email",
                        sortField: 'mail_client',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "telelephone_client",
                        title: "Téléphone",
                        sortField: 'telelephone_client',
                        titleClass: 'text-uppercase text-bold',
                    },
                    {
                        name: "adresse_client",
                        title: "Adresse",
                        sortField: 'adresse_client',
                        titleClass: 'text-uppercase text-bold',
                    },

                    {
                        name: "-",
                        title: "-",
                        width:"120px",
                        titleClass:"text-center"
                    }

                ],
                sortOrder: [
                    { field: 'numero_facture', direction: 'asc' }
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

            preview_link_photo:[]
        }

    },
    created() {
        this.vuetable.moreParams.filterEtat = "";
        this.vuetable.moreParams.criteriacolEtat = "statut_facture";
        // this.get_categorie();
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
            this.pageTitle =  "Liste des bon de commandes (" + new Intl.NumberFormat().format(Number(this.$refs.vuetable.tablePagination.total)) + " lignes)";
        },
        printelement(rowData){

            axios.get(this.BASE_URL + "/boncommande/changestatut/" + rowData.id).then((response) => {
                if (response.data.cle =="ok"){
                    window.open(this.BASE_URL+"/boncommande/apercu/"+rowData.id,'_blank');
                    Vue.$toast.open({
                        message: response.data.msg,
                        type: 'success',
                        position: 'top-right'

                    });
                    Vue.nextTick(() => this.$refs.vuetable.refresh());
                }

            })
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
        filtreEtat() {
            this.vuetable.moreParams.filterEtat = this.choix_etat;
            this.vuetable.moreParams.criteriacolEtat = "statut_facture";

            Vue.nextTick(() => this.$refs.vuetable.refresh());
        },
    }
}
