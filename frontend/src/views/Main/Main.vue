<style scoped>
/* @import "/assets/css/etalage.css"; */
/* @import "/assets/css/form.css"; */
@import "~vue-select/dist/vue-select.css";
</style>
<template>
    <main>
        <!-- <IndexBanner/> -->
        <div class="main">
            <div class="wrap">
                <div class="rsidebar span_1_of_left">
                    <section  class="sky-form">
                        <h4>Catégories ({{categories.length}})</h4>
                        <ul class="categories-list">
                            <li class="pb-2">
                                <router-link
                                    to="/"
                                    style="width: 100% !important;"
                                    class=""
                                >
                                    Tous les catégories
                                </router-link>
                            </li>
                            <li v-for="(cat, index) in categories" :key="index" >
                                <router-link
                                    :to="'/home/' + cat.id"
                                    style="width: 100% !important;"
                                    :class="(categorie_id == cat.id ? 'active' : '') + ' '"
                                >
                                    <span class="float-right">({{cat.nombreproduit}})</span>
                                    <div>{{cat.nom_categorie}} </div>
                                </router-link>
                            </li>
                        </ul>
                    </section>
                </div>
                <div class="cont span_2_of_3">
                    <!-- <section  class="sky-form">
                        <h4>Liste des produits</h4>
                    </section> -->
                    <!-- <form id="formulaire" class="login100-form validate-form">

                    </form> -->
                    <div class="mb-2">
                        <div class="input-group group-categorie">
                            <input type="text" name="nom_client" @input="getALlProduit" class="form-control" v-model="critere" placeholder="Rechercher un produit">

                            <div class="input-group-prepend ml-1">
                                <span class="input-group-text" id="basic-addon2">Catégories</span>
                            </div>
                            <v-select
                                    style="width: 150px!important;"
                                    id="nom_catogrie"
                                    :options="categories"
                                    :reduce="(m)=>m.id"
                                    placeholder="Choisissez Categorie"
                                    class="form-control"
                                    v-model="categorie_parent_id"
                                    @input="chargeSousCategorie"
                                    label="nom_categorie"
                            ></v-select>

                            <div class="input-group-prepend ml-1">
                                <span class="input-group-text" id="basic-addon3">Sous-Catégories</span>
                            </div>
                            <v-select
                                    style="width: 150px!important;"
                                    id="sous_catogrie"
                                    :options="tab_sous_categorie"
                                    :reduce="(m)=>m.id"
                                    placeholder="Choisissez sous Categorie"
                                    class="form-control"
                                    label="nom_categorie"
                                    @input="getALlProduit"
                                    v-model="sous_categorie_id"

                            ></v-select>

                        </div>


                    </div>
                    <div class="content-bottom">
                        <div class="box1">
                            <div class="row">
                                <div v-if="tab_Produit.length == 0" class="text-center col-md-12 col-lg-12 mt-5">
                                    Aucun produit trouvé
                                </div>
                                <div v-else class="text-center col-md-12 col-lg-12 mb-2">
                                    {{tab_Produit.length}} {{tab_Produit.length > 1 ? " produits trouvés" : " produit trouvé"}}
                                </div>
                                <div class="col-md-6 d-flex col-lg-6" v-for="(produit,index_produit) in tab_Produit " :key="index_produit+'mmm'">
                                    <div class="card mb-3 produit-detail" @click="gotosingle(produit.id)">
                                        <div class="card-body m-0 p-0">
                                            <div class="row">
                                                <div class="col-6">
                                                        <div class="css3">
                                                            <img style="height: 15vw;object-fit: cover;" class="etalage_thumb_image" v-if="produit.produit_photos.length > 0" :src="BASE_URL+'/assets/img/produit/'+produit.photos" />
                                                            <img class="etalage_thumb_image" v-else :src="BASE_URL+'/assets/img/produit/nophoto.png'" />
                                                        </div>
                                                </div>
                                                <div class="col-6 pt-2">
                                                    <p class="h4 m-0"> <span> {{produit.reference_produit}}</span></p>
                                                    <p class="" style="font-size: 12px!important;"><span> {{produit.designation}}</span></p>
                                                    <p class="p-0 m-0" style="border-bottom: 1px solid #00000026; font-size: 14px!important;">Description :</p>
                                                    <p class="text-justify pr-2" style="font-size: 13px!important;" v-text="(produit.description_produit.length ) <= 95 ? produit.description_produit : produit.description_produit.substr(0,95)+'...' "></p>


                                                    <div class="row">
                                                        <div class="col-12">

                                                            <div class="d-flex justify-content-between">
                                                                <div>
                                                                    <span  class="badge badge-info">{{produit.parent_categorie_nom}}</span>
                                                                    <span class="badge badge-info">{{produit.categorie}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


<!--                                    <div class="card mb-3 produit-detail">-->
<!--                                        <div class="card-body">-->
<!--                                            <a :href="'#/single/'+produit.id">-->
<!--                                                <div class="view view-fifth">-->
<!--                                                    <div class="top_box">-->
<!--                                                        <h3 class="m_1">{{produit.designation}}</h3>-->
<!--                                                        <p class="m_2">{{produit.categorie}}</p>-->
<!--                                                        <div class="grid_img">-->
<!--                                                            <div class="css3">-->
<!--                                                                &lt;!&ndash; <img style="height: 15vw;object-fit: cover;" :src="BASE_URL+'/assets/img/produit/'+produit.photos" alt=""/> &ndash;&gt;-->
<!--                                                                <img style="height: 15vw;object-fit: cover;" class="etalage_thumb_image" v-if="produit.produit_photos.length > 0" :src="BASE_URL+'/assets/img/produit/'+produit.photos" />-->
<!--                                                                <img class="etalage_thumb_image" v-else :src="BASE_URL+'/assets/img/produit/nophoto.png'" />-->
<!--                                                            </div>-->
<!--                                                            <div class="mask">-->
<!--                                                                <div class="info">{{produit.designation}}</div>-->
<!--                                                            </div>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <ul class="list">-->
<!--                                                    <li>-->
<!--                                                        <img :src="BASE_URL+'/assets/images/plus.png'" alt=""/>-->
<!--                                                        <ul class="icon1 sub-icon1 profile_img">-->
<!--                                                            <li><a class="active-icon c1" :href="'#/single/'+produit.id">plus de détail </a>-->

<!--                                                            </li>-->
<!--                                                        </ul>-->
<!--                                                    </li>-->
<!--                                                </ul>-->
<!--                                                <div class="clear"></div>-->
<!--                                            </a>-->
<!--                                        </div>-->
<!--                                    </div>-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script src="./Main.js"></script>

