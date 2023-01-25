<template>
    <div class="header-bottom">
        <div class="wrap">
            <!-- start header menu -->
            <ul class="megamenu skyblue">
                <li><a class="colorCtht" href="http://ctht.org/">Accueil</a></li>
                <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                    <router-link  id="acceuils" class="colorCtht" to="/">Tous les Produits</router-link>
                </li>
                
                <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                    <a class="colorCtht" href="#/panier/liste">Mon compte</a>
                </li>
                <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                    <a class="colorCtht" href="#/panier/liste">Mes commandes</a>
                </li>
                <li v-if="userData.admin_ctht_nom == '' || userData.admin_ctht_nom == null">
                    <a class="colorCtht" href="#/panier/liste">Panier</a>
                </li>
                
                <!--  MENU ADMINISTRATION -->
                <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null"><router-link class="colorCtht" to="/produit">Produits</router-link></li>
                <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null"><router-link class="colorCtht" to="/categorie">Catégories</router-link></li>
                <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null"><router-link class="colorCtht" to="/utilisateur">utiilsateurs</router-link></li>
                <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null"><router-link class="colorCtht" to="/commande/liste">Bon de commande</router-link></li>
                <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null"><router-link class="colorCtht" to="/commande/liste">Clients</router-link></li>

                <!-- <li v-if="userData.admin_ctht_nom != '' && userData.admin_ctht_nom != null">
                    <a class="colorCtht" href="javascript:void(0)">Espace administration</a>
                    <div class="megapanel">
                        <div class="row">
                            <div class="col1">
                                <div class="h_nav">
                                    <h4>Structures</h4>
                                    <ul>
                                        <li><router-link to="/produit">Liste des produits</router-link></li>
                                        <li><router-link to="/categorie">Catégories de produits</router-link></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col1">
                                <div class="h_nav">
                                    <h4>Utilisateurs</h4>
                                    <ul>
                                        <li><router-link to="/utilisateur">Liste des utiilsateurs</router-link></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col1">
                                <div class="h_nav">
                                    <h4>Commandes</h4>
                                    <ul>
                                        <li><router-link to="/commande/liste">Bon de commande</router-link></li>
                                        <li><a href="shop.html">Liste des clients</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col1">
                                <div class="h_nav">
                                    <h4>Clients</h4>
                                    <ul>
                                        <li><a href="shop.html">Liste des clients</a></li>
                                        <li><a href="shop.html">Ajouter un client</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li> -->
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            userData: { 
                type: Object,
                default: null,
                required: false
            },
        },
        name: "HeaderBottom"
    }
</script>

<style scoped>

</style>
