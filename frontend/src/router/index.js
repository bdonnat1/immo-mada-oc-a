import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)


const routes = [
    {
        path: '/',
        name: 'Accueil',
        component: () =>
            import ('../views/Main/Main.vue')
    },
    {
        path: '/home/:categorie_id',
        name: 'AccueilCat',
        component: () =>
            import ('../views/Main/Main.vue')
    },
    {
        path: '/register',
        name: 'Register',
        component: () =>
            import ('../views/Register/Register.vue')
    },
    {
        path: '/panier/liste',
        name: 'Panier',
        component: () =>
            import ('../views/Panier/Panier.vue')
    },
    {
        path: '/shop',
        name: 'Shop',
        component: () =>
            import ('../views/Shop/Shop.vue')
    },
    {
        path: '/single/:id_produit',
        name: 'Single',
        component: () =>
            import ('../views/Single/Single.vue')
    },
    {
        path:'/administration',
        name:'Administrtion',
        component:()=>
        import('../views/Administration/Administration.vue')
    },
    {
        path:'/produit',
        name:'Produit',
        component:()=>
        import('../views/Produit/Produit.vue')
    },
    {
        path:'/utilisateur',
        name:'Utilisateur',
        component:()=>
            import('../views/Utilisateur/Utilisateur.vue')
    },
    {
        path:'/users',
        name:'Users',
        component:()=>
            import('../views/Users/Users.vue')
    },
    {
        path:'/categorie',
        name:'Categorie',
        component:()=>
        import('../views/Categorie/Categorie.vue')
    },
    {
        path:'/register',
        name:'Register',
        component:()=>
            import('../views/Register/Register.vue')
    },
    {
        path:'/shop',
        name:'Shop',
        component:()=>
            import('../views/Shop/Shop.vue')
    },
    {
        path:'/login',
        name:'Login',
        component:()=>
            import('../views/Login/Login.vue')
    },
    {
        path:'/commande/liste',
        name:'Commandeliste',
        component:()=>
            import('../views/Commandeliste/Commandeliste.vue')
    },
    {
        path:'/admin',
        name:'LoginAdmin',
        component:()=>
            import('../views/LoginAdmin/LoginAdmin.vue')
    },
]

const router = new VueRouter({
    mode: 'hash',
    base: process.env.BASE_URL,
    routes
})

export default router
