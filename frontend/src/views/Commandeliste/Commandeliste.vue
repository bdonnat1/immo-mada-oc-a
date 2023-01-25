<style scoped>
@import "~pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
/* @import "/assets/css/form.css"; */
</style>
<template>
    <div class="main">
        <div class="wrap">
            <div class="row pt-3 pr-md-0 pr-lg-0 px-0">

                
                <div class="col-md-12">
                    
                    <section  class="sky-form">
                        <h4>{{pageTitle}}</h4>
                    </section>
                
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">DU</span>
                        </div>
                        <date-picker
                            :config="dateOptions"
                            class="form-control text-center"
                            v-model="filtreDate.dateDebut"
                            placeholder="dd/mm/yyyy"
                            name="dateDebut"
                            style="max-width: 150px"
                            @input="setDate()"
                        ></date-picker>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">AU</span>
                        </div>
                        <date-picker
                            :config="dateOptions"
                            class="form-control text-center"
                            v-model="filtreDate.dateFin"
                            placeholder="dd/mm/yyyy"
                            name="dateFin"
                            style="max-width: 150px"
                            @input="setDate()"
                        ></date-picker>
                        <select @change="filtreEtat" v-model="choix_etat" class="form-control  rounded-0">
                            <option  selected value="">Tous les bon de commande</option>
                            <option value="NOUVEAU">NOUVEAU</option>
                            <option value="ENCOURS">ENCOURS</option>
                            <option value="VALIDER">VALIDER</option>
                        </select>
                        <input type="text" class="form-control" @input="setFilter" v-model="recherche"  placeholder="Rechercher" aria-label="Rechercher" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-secondary text-uppercase" type="button" @click="setFilter">
                                Actualiser
                            </button>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-12 mt-2">
                    <vuetable
                            class="table-scroll"
                            ref="vuetable"
                            :api-url="BASE_URL+'/boncommande/fetchAll'"
                            :fields="vuetable.fields"
                            :css="vuetable.css.table"
                            :sort-order="vuetable.sortOrder"
                            :per-page="10"
                            pagination-path=""
                            data-path="data"
                            @vuetable:pagination-data="onPaginationData"
                            @vuetable:loading="onLoading"
                            @vuetable:loaded="onLoaded"
                            :append-params="vuetable.moreParams"
                    >
                        <template slot="-" scope="props">
                            <div class="table-button-container text-center btn-table">
                                <button class="btn btn-sm btn-outline-primary mr-1" type="button" @click=" printelement(props.rowData)" title="Afficher le bon de commande"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-outline-dark mr-1" type="button" @click=" printelement(props.rowData)" title="Imprimer le bon de commande"><i class="fa fa-print"></i></button>
                                <button class="btn btn-sm btn-outline-danger"  type="button" @click="deleteproduit(props.rowData)" title="Supprimer le bon de commande"> <i class="fa fa-trash"></i></button>
                            </div>
                        </template>
                        <template slot="statut_facture" scope="props">
                            <div class="text-center">
                                <h5 class="m-0 p-0">
                                    <span v-if="props.rowData.statut_facture == 'NOUVEAU'" class="badge badge-secondary text-white" style="width: 100%">{{props.rowData.statut_facture}}</span>
                                    <span v-if="props.rowData.statut_facture == 'ENCOURS'" class="badge badge-warning text-white" style="width: 100%">{{props.rowData.statut_facture}}</span>
                                    <span v-if="props.rowData.statut_facture == 'VALIDER'" class="badge badge-success text-white" style="width: 100%">{{props.rowData.statut_facture}}</span>
                                </h5>
                            </div>
                        </template>

                    </vuetable>
                    <vuetable-pagination
                            ref="pagination"
                            :css="vuetable.css.pagination"
                            @vuetable-pagination:change-page="onChangePage">
                    </vuetable-pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script src="./Commandeliste.js">

</script>
