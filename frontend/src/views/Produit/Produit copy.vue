<template>
  
  <div class="container-fluid">
    <h1>Liste Produit</h1>
    <div class="btn-toolbar mb-2 mb-md-0 offset-11">
          <div class="float-right ">
              <button @click="addProduit" type="button" class="btn btn-sm btn-outline-dark rounded-0">
               <i class="fas fa-plus-circle"></i>
                Ajouter
               </button>
           </div>
       </div>
       <div class="row">
          <div class="offset-10 pb-2 col-md-2 mt-2">
            <input type="text" placeholder="Designation" class="form-control-sm form-control" @keyup="setFilter" v-model="recherche" >
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
                :append-params="vuetable.moreParams"    
        >
          <template slot="actions" scope="props">
            <div class="table-button-container" >
             <div>
               <b-dropdown size="lg"  variant="link" toggle-class="text-decoration-none" no-caret>
                <template #button-content>
                  <p style="font-weight: bold;color:black;" >&#x22EE;</p> <span class="sr-only">Search</span>
                </template>
                  <b-dropdown-item  @click=" Editproduit(props.rowData);Editphotos(props.rowData); Editvariation(props.rowData);onReset()">
                   <button class="btn btn-outline-secondary btn-sm mr-2"> <span class="fas fa-pencil-alt"></span></button>&nbsp;Editer
                  </b-dropdown-item> 
                <b-dropdown-item @click="deleteproduit(props.rowData)">  
                  <button class="btn btn-outline-danger btn-sm mr-1"><span class="fas fa-trash"></span></button>&nbsp; Supprimer
                </b-dropdown-item>
              </b-dropdown>
             </div>
            </div>
          </template>
            <template slot="variation" scope="props">
              <div v-if="props.rowData.variation && props.rowData.prix != ''" >
                 <button 
                     class="btn btn-outline-secondary btn-sm "   
                     @click="$bvModal.show('variation'); getvariation(props.rowData)" 
                    >
                    <span class="fas fa-eye"></span> 
                   
                 </button> 
              </div>
                <div v-else> </div>                     
            </template>
            <template slot="photos" scope="props">
              <div v-if="props.rowData.photos_produit != null" >
                 <button 
                     class="btn btn-outline-secondary btn-sm "   
                     @click="$bvModal.show('photos'); get_photos_produit(props.rowData)"
                    > 
                    <i class="far fa-image"></i>
                 </button> 
              </div>
                <div v-else> </div>                     
            </template>
        </vuetable>
         <vuetable-pagination
                ref="pagination"
                :css="vuetable.css.pagination"
                @vuetable-pagination:change-page="onChangePage">
         </vuetable-pagination>
    <!-- begin modal variation -->

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
                  <div class="col-6 mt-2" >
                    <div v-if="variation.photos  != ''" >
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
                    <div v-if="photos.photos != ''" >
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
        <form method="post"  id="formulaire" v-on:submit.prevent="save" enctype="multipart/form-data">
          <input type="hidden" name="produit_id" v-model="produit.id">
          <div class="row">

            <div class="col-4">
               <h3>Article</h3>
                <div class="form-group">
                    <label for="">Reference Produit</label>
                      <input type="text" name="reference_produit" class="form-control" v-model="produit.reference_produit" placeholder="Reference produit">
                
                 </div>
              
                <div class="form-group">
                 <label for="">Designation</label>
                    <input type="text" name="designation"  class="form-control" placeholder="Designation" v-model="produit.designation" >
            
                </div>
              
                  <div class="form-group" >
                    <label for="">Categorie</label>
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
                   <input type="text" name="statut" id="type" v-model="produit.statut" class="form-control d-none form-control-sm">  
                </div>
             
              </div>
              <div class="col-6">
               <h3>Variation</h3>
                
                <table class="table table-bordered">
                  <thead> </thead>
                  <tbody>
                    <tr v-for="(variation,index ) in variation_produit" v-bind:key="index"  class="form-group">
                      <td class="mt-0">
                         <label for="">Photos</label>
                          <b-form-file
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
                        <label for="">Libellé</label>
                          <input type="text" name="variation[]" placeholder="Libellé" class="form-control"  v-model="variation.variation" >
                          <input type="hidden" name="variation_id[]"  >
                       </td>
                       <td >
                        <label for="">Prix</label>
                          <input type="number" name="prix[]" placeholder="Prix" class="form-control "  v-model.number="variation.prix" >
                       </td>
                        <td>
                          <button type="button" id="add_fields" class= "  mt-4 btn btn-dark btn-sm  p-1"  @click="add_variation"><i class="fas fa-plus"></i> </button>
                           <span class="p-3  " v-if="variation_produit.length >1">
                             <button type="button" id="removes" class= "  mt-4 btn btn-danger btn-sm p-1" @click="remove_variation(index)"><i class="fas fa-times"></i> </button>
                          </span>
                        </td>
                    </tr>
                  </tbody>
                </table>
              
                <div class="col-4">
               <h3>Photos</h3>

              <label for="">Photos</label>
                <b-form-file
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
                 <div>
                    <ul v-for="(img,index) in images" :key="index">
                         <li >
                        {{ img.name }}
                         </li>  
                        <input type="hidden" name="file[]" v-model="img.name">
                      </ul>
                  </div>
              </div>
              </div>
            </div>
            <div class="modal-footer">
              <b-button variant="secondary" @click="closeModal()">Fermer</b-button>
              <b-button type="submit" variant="primary" >Enregistrer</b-button>
            </div>
        </form>
     </b-modal>
    </div>
     <!-- end modal formulaire -->
</template>

<script src="./Produit.js"></script>
<style scoped>
  @import "~vue-select/dist/vue-select.css";
  .dropdown-menu
  {
    height: auto;
    max-height: 200px;
    overflow-x: hidden;
  }
  .modal-fullscreen {
  width: 100%;
  max-width: 100%;
  }
 
</style>


