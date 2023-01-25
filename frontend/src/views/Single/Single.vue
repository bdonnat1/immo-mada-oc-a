<style scoped>
    /* @import "/assets/css/etalage.css"; */
    /* @import "/assets/css/form.css"; */
    @import '~vue-photo-zoom-pro/dist/style/vue-photo-zoom-pro.css';
    @import '~vue-slick-carousel/dist/vue-slick-carousel.css';
    @import '~vue-slick-carousel/dist/vue-slick-carousel-theme.css';
</style>
<template>
    <!-- <div class="single"> -->
    <div class="">
        <div class="wrap">
            <div class="rsidebar span_1_of_left">
                <section class="sky-form">
                    <h4>Catégories</h4>
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
                        <li v-for="(cat, index) in categories" :key="index">
                            <router-link
                                    :to="'/home/' + cat.id"
                                    style="width: 100% !important;"
                                    :class="(categorie_id == cat.id ? 'active' : '') + ' '"
                            >
                                <span class="float-right">({{cat.nombreproduit}})</span>
                                <div>{{cat.nom_categorie}}</div>
                            </router-link>
                        </li>
                    </ul>
                </section>
            </div>
            <div class="cont span_2_of_3">
                <div class="row">
                    <!-- <div class="labout span_1_of_a1 pt-5"> -->
                    <div class="col-md-5 pt-5">
                        <ul id="etalage">
                            <li>
                                <a href="javascript:void(0)">
                                    <vue-photo-zoom-pro :url="current_photo_url" :high-url="current_photo_url"/>
                                </a>
                            </li>
                        </ul>

                        <VueSlickCarousel v-bind="slider_settings" v-if="produit_photos.length > 0">
                            <div v-for="(photo, index) in produit_photos" :key="index">
                                <img :src="BASE_URL+'/assets/img/produit/'+photo.photos" @click="changeImage(photo)"/>
                            </div>
                        </VueSlickCarousel>
                    </div>

                    <div class="cont1 col-md-7">
                        <h3 class="m_3 designation">{{produits.designation}}</h3>
                        <h3 class=""><span
                                class="variation">Superficie : {{FormatNombre(produits.superficie)}} Km²</span></h3>

                        <div class="price_single">
                            <span class="actual"> Longeur {{FormatNombre(produits.longueur)}} m </span> <br>
                            <span class="actual"> Largeur  {{ FormatNombre(produits.largeur)}} m </span>
                        </div>

                        <ul class="options">
                            <h4 class="m_9">Variations</h4>
                            <li v-for="(v,index_v) in tab_Produit.value_variation" :key="index_v+'888'">
                                <a href="javascript:void(0)" :class="variation.id == v.id ? 'active' : ''"
                                   @click="choiceVariation(v)">{{v.variation}}</a>
                            </li>
                            <div class="clear"></div>
                        </ul>
                        <ul class="options">
                            <h4 class="m_9">Catégorie</h4>
                            <li>
                                <router-link
                                        :to="'/home/' + produits.categorie_id"
                                        class="text-uppercase"
                                >
                                    {{produits.categorie}}
                                </router-link>
                            </li>
                            <div class="clear"></div>
                        </ul>
                        <div class="btn_form">
                            <button @click="addPanier" class="add-to-card" type="button"><i
                                    class="fa fa-shopping-cart mr-1"></i> Ajouter dans le panier
                            </button>
                        </div>

                        <div class="toogle">
                            <h3 class="m_3">Description</h3>
                            <p class="m_text">{{produits.description_produit}}</p>
                        </div>

                        <div class="col-12" v-show="show_map">
                            <gmap-map
                                    :center="center"
                                    :zoom="20"
                                    style="width: 100%; height: 350px"
                                    :options="{
                                       zoomControl: true,
                                       mapTypeControl: false,
                                       scaleControl: false,
                                       streetViewControl: false,
                                       rotateControl: false,
                                       fullscreenControl: true,
                                       disableDefaultUi: false
                                     }"
                            >
                                <gmap-marker
                                        :key="index"
                                        v-for="(m, index) in markers"
                                        :position="m.position"
                                        :clickable="true"
                                        :draggable="true"
                                        @click="center=m.position"
                                ></gmap-marker>
                            </gmap-map>
                        </div>

                    </div>
                    <div class="col-12 pt-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-bold">CONTACTEZ-NOUS</h5>
                                <p class="text-uppercase" style="font-size: 13px ; color: #888888" >ce bien vous intéresse?</p>
                                <form id="formulaire" method="post" v-on:submit.prevent="onSubmit">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input required placeholder="Nom" type="text" class="form-control" name="nom" v-model="formData_sendMessage.nom">
                                            </div>
                                            <div class="form-group">
                                                <input required placeholder="Adresse email" type="email" class="form-control" name="adresse_mail" v-model="formData_sendMessage.adresse_mail">
                                            </div>
                                            <div class="form-group">
                                                <input required placeholder="Contact" type="text" class="form-control" name="telephone" v-model="formData_sendMessage.telephone">
                                            </div>
                                            <div class="form-group">
                                            <textarea placeholder="votre message" name="msg_envoyer" id="msg_envoyer" cols="30"
                                                      rows="5" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" v-model="formData_sendMessage.lien" name="lien">
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-primary" type="submit">Envoyer</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>


                <!-- <div class="toogle">
                    <h3 class="m_3">Description</h3>
                    <p class="m_text">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                        euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis
                        nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis
                        autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
                        eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent
                        luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta
                        nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</p>
                </div> -->

            </div>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script src="./Single.js"></script>
