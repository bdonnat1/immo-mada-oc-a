<style scoped>
/* @import "/assets/css/etalage.css"; */
/* @import "/assets/css/form.css"; */
</style>
<template>
    <div class="main">

            <div class="wrap">
                <div class="content-top">
                    <h3 class="m_3">{{pageTitle}}</h3>
                    <div class="pb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" @keyup="setFilter" v-model="recherche" placeholder="Rechercher" aria-label="Rechercher" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary text-uppercase" @click="setFilter" type="button">
                                    Actualiser
                                </button>
                                <button class="btn btn-secondary text-uppercase" type="button" @click="addProduit">
                                    <i class="fa fa-plus"></i>
                                    Ajouter un produit
                                </button>
                            </div>
                        </div>
                    </div>

                    <vuetable
                            class="table-scroll"
                            ref="vuetable"
                            :api-url="BASE_URL+'/produit/fetchAll'"
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
                            track-by="id"
                    >
                        <template slot="photos" scope="props">
                            <div class="card " style="height: 80px!important; width: 80px!important; overflow: hidden!important;padding:0.5rem!important;">
                                <img :src="base_url+'/assets/img/produit/'+props.rowData.photos" alt="">
                            </div>
                        </template>
                        <template slot="-" scope="props">
                            <div class="table-button-container text-center">
                                <button style="width: 30px" class="btn btn-sm btn-outline-dark" type="button" @click=" editRow(props.rowData)"><i class="fa fa-pencil-alt"></i></button>
                                <button style="width: 30px" class="btn ml-1 btn-sm btn-outline-danger"  type="button" @click="deleteproduit(props.rowData)"> <i class="fa fa-trash"></i></button>
                            </div>
                        </template>
                        <template slot="variation" scope="props">
                            <div v-for="(variationx, indexx) in props.rowData.variation" :key="indexx">
                                <!-- - {{variationx.variation}} -- {{variationx.prix}} -->
                                <table class="table-interne" width="100%" border="0">
                                    <tr>
                                        <td>{{variationx.variation}}</td>
                                        <td class="text-right">
                                            <!-- {{variationx.prix}} -->
                                            {{
                                                new Intl.NumberFormat('fr', {
                                                minimumFractionDigits: 0,
                                                }).format(variationx.prix) + ' Ar'
                                            }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </template>

                    </vuetable>
                    <vuetable-pagination
                            ref="pagination"
                            :css="vuetable.css.pagination"
                            @vuetable-pagination:change-page="onChangePage">
                    </vuetable-pagination>

                    <div class="clear"></div>
                </div>
            </div>




        <b-modal
                id="variation"
                size="md"
                scrollable
                hide-footer
                centered
                no-close-on-backdrop
        >
            <template #modal-title>
                Variation Produit
            </template>
            <div class="text-center" v-for="(variation , index) in produit_variation" :key="index">

                <div class="row">
                    <div class="col-3 mt-5">

                        {{ variation.variation }}
                    </div>
                    <div class="col-3 mt-5">
                        {{ variation.prix}} &nbsp;Ar
                    </div>
                    <div class="col-6 mt-2">
                        <div v-if="variation.photos  != ''">
                            <img
                                    :src="'/uploads/variation/' + variation.photos"
                            />
                        </div>
                    </div>

                </div>

            </div>
        </b-modal>
        <!-- end modal variation -->
        <!-- begin modal photos -->

        <b-modal
                id="photos"
                size="lg"
                scrollable
                hide-footer
                centered
                no-close-on-backdrop
        >
            <template #modal-title>
                Photos Produit
            </template>
            <div class="text-center" v-for="(photos , index) in photos_produit" :key="index">
                <div class="row">

                    <div class=" mt-4 ml-5">
                        <div v-if="photos.photos != ''">
                            <img
                                    :src="'/uploads/produit/'+photos.photos"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
        <!-- end modal photos -->

        <!-- Modal Formulaire-->

        <b-modal
                id="myCustomModal"
                :title="titlemodal"
                size="xlg"
                scrollable
                hide-footer
                no-close-on-backdrop
        >
            <form method="post" id="formulaire" v-on:submit.prevent="save" enctype="multipart/form-data">
                <input type="hidden" name="produit_id" v-model="produit.id">
                <div class="row">
                    <div class="col-12 col-lg-4 col-md-4">
                        <section  class="sky-form">
                            <h4>Information sur le produit</h4>
                        </section>
                        <div class="form-group">
                            <label for="">Reference Produit</label>
                            <input type="text" name="reference_produit" class="form-control"
                                   v-model="produit.reference_produit" placeholder="Reference produit">

                        </div>

                        <div class="form-group">
                            <label for="">Designation</label>
                            <input required type="text" name="designation" class="form-control" placeholder="Designation"
                                   v-model="produit.designation">

                        </div>

                        <div class="row">
                            <div class="col-lg-6 p-0 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="longueur">Longueur</label>
                                    <input id="longueur" @input="calculSuperficie" v-model="produit.longueur" min="0" name="longueur" type="number" step="any" class=" form-control">
                                </div>
                            </div>
                            <div class="col-12 p-0 pl-md-1 pl-lg-1 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label for="largeur">Largeur</label>
                                    <input type="number" @input="calculSuperficie" v-model="produit.largeur" min="0" id="largeur" name="largeur" step="any" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="superficie">Superficie</label>
                            <input type="number" v-model="produit.superficie" min="0" name="superficie" step="any" class="form-control" id="superficie">
                        </div>

                        <div class="form-group">
                            <label for="position_map">Position map</label>
                            <input placeholder="position map" type="text" class="form-control" name="position_map" id="position_map" v-model="produit.position_map">
                        </div>

                        <div class="form-group">
                            <label for="">Catégorie</label>
                            <v-select
                                    :options="categorie"
                                    :reduce="(m)=>m.id"
                                    placeholder="Choisissez Categorie"
                                    label="nom_categorie"
                                    v-model="produit.categorie_id"
                            ></v-select>
                            <input type="hidden" name="categorie_id" v-model="produit.categorie_id" id="categorie_id">
                        </div>

                        <div class="form-group">
                            <label for="statut">Statut</label>
                            <v-select
                                    :options="statut"
                                    :reduce="(m) => m.value"
                                    label="value"
                                    placeholder="Choisissez Statut"
                                    v-model="produit.statut">
                            </v-select>
                            <input type="text" name="statut" id="type" v-model="produit.statut"
                                   class="form-control d-none form-control-sm">
                        </div>
                        <div class="form-group">
                            <label for="description_produit"> Déscription</label>
                            <textarea v-model="produit.description_produit" class="form-control form-control-sm" name="description_produit" id="description_produit" cols="30"
                                      rows="5">

                            </textarea>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-8 col-12">
                        <section  class="sky-form">
                            <h4>Variations</h4>
                        </section>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="80px">Photos</th>
                                    <th>libellé</th>
                                    <th width="120px">Prix</th>
                                    <th width="40px" class="text-center">-</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(variation,index ) in variation_produit" v-bind:key="index" class="form-group">
                                <td class="mt-0">
                                    <div class="d-flex flex-row">
                                        <div class="card no-border" style="height: 60px!important; width: 60px!important; overflow: hidden!important;padding:0.5rem!important; border:none;">
                                            <img
                                                v-show="preview_link_variation[index].image != '/assets/img/variation/' && preview_link_variation[index].image != ''"
                                                    class="card-img-top img-fluid"
                                                    :src="preview_link_variation[index].image"
                                                    alt="image"
                                                    style="width: 100%!important;height: auto!important;"
                                            />
                                        </div>
                                        <button @click="OpenFile(index)" class="btn btn-default btn-sm align-self-auto" type="button">
                                            <i class="fa fa-camera"></i>
                                        </button>
                                    </div>

                                    <b-form-file
                                            :id="'upload'+index"
                                            class="d-none"
                                            v-model="fichier"
                                            :state="Boolean(fichier)"
                                            placeholder="Choisissez votre photos"
                                            drop-placeholder="Placer votre ici..."
                                            @change=" uploadFile_variation($event,index)"
                                            name="file-input"
                                            accept="image/*"
                                    ></b-form-file>

                                    <input type="hidden" name="photos[]" v-model="variation.photos">
                                </td>
                                <td>
                                    <input type="hidden" name="id_variation[]" v-model="variation.id">
                                    <input type="text" name="variation[]" placeholder="Libellé" class="form-control border-0"
                                           v-model="variation.variation">
<!--                                    <input type="hidden" name="variation_id[]">-->
                                </td>
                                <td>
                                    <input type="number" name="prix[]" placeholder="Prix" class="form-control border-0 text-right"
                                           v-model.number="variation.prix">
                                </td>
                                <td>
                                    <button v-show="variation_produit.length - 1 == index" type="button" id="add_fields" class=" btn btn-dark btn-sm "
                                            @click="add_variation"><i class="fas fa-plus"></i>
                                    </button>
                                    <button v-if="variation_produit.length >1 && variation_produit.length - 1 != index" type="button" id="removes" class=" btn btn-danger btn-sm"
                                            @click="remove_variation(index,variation)"><i class="fas fa-times"></i>
                                    </button>
<!--                                    <span class="p-3  " v-if="variation_produit.length >1">-->
<!--                                        -->
<!--                                    </span>-->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <section  class="sky-form">
                                <h4>Photos du produit</h4>
                            </section>
                            <b-form-file
                                    id="photos"
                                    v-model="files"
                                    :state="Boolean(file)"
                                    placeholder="Choisissez votre fichier"
                                    drop-placeholder="Placer votre ici..."
                                    v-on:input="onChange"
                                    @change="uploadFile($event);onReset()"
                                    accept="image/*"
                                    name="file-input"
                                    multiple plain>
                            </b-form-file>
                        </div>

                        <div class="row">
                            <div class="col-3" v-for="(img,index_img) in preview_link_photo" :key="index_img+'llll'">
                                <input type="hidden" name="file[]" v-model="img.nom_image">
                                <input type="hidden" v-model="img.id" name="id_produit[]">
                                <div class="card" style="height: 250px!important;overflow: hidden!important;padding:0.5rem!important;">
                                    <img
                                            class="card-img-top img-fluid"
                                            alt="Image"
                                            :src="preview_link_produit[index_img].nom_image"
                                            style="width: 100%!important;height: auto!important;"
                                    />
                                </div>
                                <button @click="deleteImage(index_img,img)" type="button" class="btn btn-block btn-danger btn-sm mb-3 mt-1"><i class="fa fa-times"></i> Supprimer</button>

                            </div>
                        </div>
<!--                        <div class="col-4">-->
<!--                            <h3>Photos</h3>-->

<!--                            <label for="">Photos</label>-->
<!--                            <b-form-file-->
<!--                                    v-model="files"-->
<!--                                    :state="Boolean(file)"-->
<!--                                    placeholder="Choisissez votre fichier"-->
<!--                                    drop-placeholder="Placer votre ici..."-->
<!--                                    v-on:input="onChange"-->
<!--                                    @change="uploadFile($event);onReset()"-->
<!--                                    accept="image/*"-->
<!--                                    name="file-input"-->
<!--                                    multiple plain>-->
<!--                            </b-form-file>-->
<!--                            <div>-->
<!--                                <ul v-for="(img,index) in images" :key="index">-->
<!--                                    <li>-->
<!--                                        {{ img.name }}-->
<!--                                    </li>-->
<!--                                    <input type="hidden" name="file[]" v-model="img.name">-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
                <div class="modal-footer">
                    <b-button variant="secondary" @click="closeModal()">Fermer</b-button>
                    <b-button type="submit" variant="primary">Enregistrer</b-button>
                </div>
            </form>
        </b-modal>

    </div>
</template>

<script src="./Produit.js"></script>
<style scoped>
    @import "~vue-select/dist/vue-select.css";

    .dropdown-menu {
        height: auto;
        max-height: 200px;
        overflow-x: hidden;
    }

    .modal-fullscreen {
        width: 100%;
        max-width: 100%;
    }
    .row{
        margin-left: 0!important;
        margin-right: 0!important;
    }
</style>


