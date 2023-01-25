
<style scoped>
@import "~vue-select/dist/vue-select.css";
.dropdown-menu {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
}
</style>
<template>
    <div class="main">
        <div class="wrap">
            <div class="content-top">
            <h3 class="m_3">{{pageTitle}}</h3>

                <div class="pb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" @input="setFilter" v-model="recherche" placeholder="Rechercher" aria-label="Rechercher" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary text-uppercase" @click="setFilter" type="button">
                                Actualiser
                            </button>
                            <button class="btn btn-secondary text-uppercase" type="button" @click="addCategorie">
                                <i class="fa fa-plus"></i>
                                Ajouter une catégorie
                            </button>
                        </div>
                    </div>
                </div>
                <vuetable
                        class="table-scroll"
                        ref="vuetable"
                        :api-url="BASE_URL+'/categorie/fetchAll'"
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
                    <template slot="action" scope="props">
                        <button class="btn btn-sm btn-outline-dark" type="button" @click="Editcategorie(props.rowData)"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger ml-1" type="button" @click="deletecategorie(props.rowData)"><i class="fa fa-trash"></i></button>
                    </template>

                    <template slot="photos" scope="props">
                        <img style="width: 80px; height: 80px; object-fit: cover" v-if="props.rowData.photos !=''" :src=" BASE_URL+'/assets/img/categorie/'+props.rowData.photos" alt="">
                        <img style="width: 80px; height: 80px; object-fit: cover" v-else :src=" BASE_URL+'/assets/img/produit/nophoto.png'" alt="">
                    </template>


                </vuetable>
                <vuetable-pagination
                        ref="pagination"
                        :css="vuetable.css.pagination"
                        @vuetable-pagination:change-page="onChangePage">
                </vuetable-pagination>
            </div>
        </div>

        <!-- Modal Formulaire-->
        <b-modal
                id="modal"
                :title="titlemodal"
                size="lg"
                scrollable
                hide-footer
                centered
                no-close-on-backdrop
        >
            <form method="post" id="formulaire" v-on:submit.prevent="save" enctype="multipart/form-data">
                <input type="hidden" name="categorie_id" v-model="categorie.id">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nom_categorie">Catégorie Parent</label>
                            <v-select
                                    id="nom_categorie"
                                    :options="tab_categorie_parent"
                                    :reduce="(m)=>m.nom_categorie"
                                    placeholder="Choisissez Categorie"
                                    label="nom_categorie"
                                    v-model="categorie.nom_categorie"
                            ></v-select>

                            <input type="hidden" name="nom_categorie" class="form-control"
                                   v-model="categorie.nom_categorie" placeholder="Nom catégorie">
                        </div>
                        <div class="form-group">
                            <label for="sous-categorie">Catégorie</label>
                            <table class="table">
                                <tr v-for="(c,index_categorie) in categorie.child_categorie" :key="index_categorie">
                                    <td style="border: none !important;padding: 0!important; padding-bottom: 5px !important;">
                                        <input required type="text" class="form-control" name="nom_categorie_child[]" v-model="c.nom_categorie">
                                        <input type="hidden" class="form-control" name="categorie_parent_child[]" v-model="c.categoie_parent">
                                        <input type="hidden" class="form-control" name="id_child[]" v-model="c.id">
                                    </td>

<!--                                    <td width="40px" style="border: none !important;padding: 0!important; padding-bottom: 5px !important;">-->
<!--                                        <button @click="PushCategorie" v-show="index_categorie === (categorie.child_categorie.length - 1)" class="btn btn-outline-dark ml-1" type="button"><i class="fa fa-plus"></i></button>-->
<!--                                        <button @click="PopCategorie(index_categorie)" v-show="index_categorie !== (categorie.child_categorie.length - 1)" class="btn btn-outline-danger ml-1" type="button"><i class="fa fa-times"></i></button>-->
<!--                                    </td>-->
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" type="text" name="description" class="form-control"
                                      placeholder="Description" v-model="categorie.description"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <input type="hidden" name="file" v-model="categorie.photos">
                        <label for="">Photos</label>
                        <b-form-file
                                v-model="file"
                                :state="Boolean(file)"
                                placeholder="Choisissez votre photos"
                                drop-placeholder="Placer votre ici..."
                                @change="uploadFile($event)"
                                accept="image/*"
                                name="file-input"
                        ></b-form-file>
                        <div class="card">
                            <!-- {{preview_link}} -->
                            <img v-show="preview_link != '/assets/img/default/camera.png'" :src="preview_link" alt="">
                            <!-- this.BASE_URL+'/assets/img/produit/nophoto.png' -->
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" v-model="categorie.id" name="id">
                    <b-button variant="secondary" @click="closeModal()">Fermer</b-button>
                    <b-button type="submit" variant="primary">Enregistrer</b-button>
                </div>
            </form>
        </b-modal>
        <!-- end modal formulaire -->
        <!-- begin modal photos -->

        <b-modal
                id="photos"
                size="md"
                scrollable
                hide-footer
                centered
                no-close-on-backdrop
        >
            <template #modal-title>
                Photos Categorie
            </template>
            <div class="text-center" v-for="(photos , index) in photos_categorie" :key="index">
                <div class="row">
                    <div class="col-3 mt-5">
                        {{ photos.nom_categorie }}
                    </div>
                    <div class="col-9 mt-0">
                        <div v-if="photos.photos != ''">
                            <img
                                    :src="'/uploads/categorie/' + photos.photos"
                            />
                        </div>

                    </div>
                </div>
            </div>
        </b-modal>
        <!-- end modal photos -->
    </div>
</template>

<script src="./Categorie.js"></script>


