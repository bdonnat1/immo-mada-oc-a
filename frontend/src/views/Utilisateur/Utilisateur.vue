
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



<template>
    <div class="main">

        <div class="wrap">
            <div class="content-top">
                <h3 class="m_3">LISTE DES UTILISATEURS</h3>

                
                <div class="pb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" @input="setFilter" v-model="recherche" placeholder="Rechercher" aria-label="Rechercher" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary text-uppercase" @click="setFilter" type="button">
                                Actualiser
                            </button>
                            <button class="btn btn-secondary text-uppercase" type="button" @click="AddData">
                                <i class="fa fa-plus"></i>
                                Nouveau
                            </button>
                        </div>
                    </div>
                </div>
                <!-- <div class="btn-toolbar mb-2 mb-md-0 offset-11">
                    <div class="float-right ">
                        <button @click="AddData" type="button" class="btn btn-sm btn-outline-dark rounded-0">
                            <i class="fas fa-plus-circle"></i>
                            Ajouter
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-10 pb-2 col-md-2 mt-2">
                        <input type="text" placeholder="Designation" class="form-control-sm form-control"
                               @keyup="setFilter" v-model="recherche">
                    </div>

                </div> -->
                <vuetable
                        class="table-scroll"
                        ref="vuetable"
                        :api-url="BASE_URL+'/utilisateur/fetchall'"
                        :fields="vuetable.fields"
                        :css="vuetable.css.table"
                        :sort-order="vuetable.sortOrder"
                        :per-page="10"
                        pagination-path=""
                        data-path="data"
                        @vuetable:pagination-data="onPaginationData"
                        :append-params="vuetable.moreParams"
                >
                    <template slot="photos_utilisateur" scope="props">
                        <div class="card " style="height: 80px!important; width: 80px!important; overflow: hidden!important;padding:0.5rem!important;">
                            <img :src=" (props.rowData.photos_utilisateur != '') ? BASE_URL+'/assets/img/utilisateur/'+props.rowData.photos_utilisateur : BASE_URL+'/assets/img/default/personne.png'" alt="">
                        </div>
                    </template>
                    <template slot="-" scope="props">
                        <div class="table-button-container text-center">
                            <button style="width: 30px" class="btn btn-sm btn-outline-dark" type="button" @click=" editRow(props.rowData)"><i class="fa fa-pencil-alt"></i></button>
                            <button style="width: 30px" class="btn ml-1 btn-sm btn-outline-danger"  type="button" @click="deleteRow(props.rowData)"> <i class="fa fa-trash"></i></button>
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
                id="crudModal"
                :title="titlemodal"
                size="lg"
                scrollable
                hide-footer
                no-close-on-backdrop
        >
            <form method="post" id="formulaire" v-on:submit.prevent="onsubmit">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-12 p-0">
                        <div class="row">
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="nom_utilisateur">Nom</label>
                                    <input required type="text" id="nom_utilisateur" v-model="crudData.nom_utilisateur" class="form-control form-control-sm" name="nom_utilisateur">
                                </div>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="login_utilisateur">Login</label>
                                    <input required type="text" id="login_utilisateur" v-model="crudData.login_utilisateur" class="form-control form-control-sm" name="login_utilisateur">
                                </div>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="role_utilisateur">Rôle</label>
                                    <select aria-required="true" name="role_utilisateur" v-model="crudData.role_utilisateur" class="form-control-sm form-control" id="role_utilisateur">
                                        <option value=""> Choisissez un rôle</option>
                                        <option v-for="(r,index_role) in role_user" :key="index_role+'r'" :value="r.text"> {{r.text}}</option>
                                    </select>
<!--                                    <input type="text" id="login_utilisateur" v-model="crudData.role_utilisateur" class="form-control form-control-sm" name="login_utilisateur">-->
                                </div>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="mail_utilisateur">Email</label>
                                    <input type="text" id="mail_utilisateur" v-model="crudData.mail_utilisateur" class="form-control form-control-sm" name="mail_utilisateur">
                                </div>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="telephone_utilisateur">Téléphone</label>
                                    <input type="text" id="telephone_utilisateur" v-model="crudData.telephone_utilisateur" class="form-control form-control-sm" name="telephone_utilisateur">
                                </div>
                            </div>
                            <div class="col-md-12 p-0">
                                <div class="form-group">
                                    <label for="statut_utilisateur">Statut</label>
                                    <select aria-required="true" name="statut_utilisateur" v-model="crudData.statut_utilisateur" class="form-control-sm form-control" id="statut_utilisateur">
                                        <option value=""> Choisissez un rôle</option>
                                        <option v-for="(r,index_role) in statut_user" :key="index_role+'u'" :value="r.text"> {{r.text}}</option>
                                    </select>
                                    <!--                                    <input type="text" id="login_utilisateur" v-model="crudData.role_utilisateur" class="form-control form-control-sm" name="login_utilisateur">-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-12 pl-md-3 pl-lg-3 p-0">
                        <div class="form-group">
                            <label for="photos_utilisateur">Photoss</label>
                            <div class="card" style="padding: 0.5rem !important;height: 60vh!important;" >
                                    <img style="height: 60vh;object-fit: cover;" :src="preview_link" alt=""/>

                            </div>


                            <button style="width: 100%!important;" class="btn btn-danger btn-sm" type="button"><i class="fa fa-times"></i> Supprimer la photo</button>
                            <b-form-file
                                    v-model="photos_utilisateur"
                                    :state="Boolean(photos_utilisateur)"
                                    placeholder="photos"
                                    drop-placeholder="Placer votre ici..."
                                    @change="uploadImage($event)"
                                    accept="image/*"
                                    id="file-input"
                                    class="mt-1"
                            ></b-form-file>
                            <input type="text" class="d-none" name="photos_utilisateur" v-model="crudData.photos_utilisateur" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" class="d-none" v-model="crudData.id" name="id">
                    <b-button variant="secondary" @click="closeModal()">Fermer</b-button>
                    <b-button type="submit" variant="primary">Enregistrer</b-button>
                </div>
            </form>
        </b-modal>

    </div>
</template>

<script src="./Utilisateur.js"></script>