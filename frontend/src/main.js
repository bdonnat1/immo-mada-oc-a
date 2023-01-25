import Vue from 'vue'
import './plugins/fontawesome'
import App from './App.vue'


import axios from 'axios'
import i18n from './i18n'
import VueCookies from 'vue-cookies'
import Tools from './tools'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import datePicker from 'vue-bootstrap-datetimepicker'


import VueToast from 'vue-toast-notification'

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueCookies);
Vue.use(datePicker);
Vue.use(VueToast);
let instance = Vue.$toast.open('You did it!');
instance.dismiss();
Vue.$toast.clear();

if (process.env.NODE_ENV == 'development') {
    Vue.BASE_URL = Vue.prototype.BASE_URL = '';
} else {
    // Production
    // Vue.BASE_URL = Vue.prototype.BASE_URL = '/nosproduits';
    Vue.BASE_URL = Vue.prototype.BASE_URL = '/Immo/';
    // Vue.BASE_URL = Vue.prototype.BASE_URL = '/dna_commerce/';
}
Vue.UNITE = Vue.prototype.UNITE = '';
Vue.LOGO = Vue.prototype.LOGO = 'CHANTIER';

Vue.NOM_SOCIETE = Vue.prototype.NOM_SOCIETE = 'SMCM';
// Vue.NOM_SOCIETE = Vue.prototype.NOM_SOCIETE = 'ECONS';

Vue.NOMBREDECIMAL = Vue.prototype.NOMBREDECIMAL = 2;


import router from './router'

Vue.config.productionTip = false


axios.get(Vue.BASE_URL + "/utilisateur/getuserconfig").then(function () {
});

var user = {
    admin_ctht_id: Vue.$cookies.get("admin_ctht_id"),
    admin_ctht_login: Vue.$cookies.get("admin_ctht_login"),
    admin_ctht_nom: Vue.$cookies.get("admin_ctht_nom"),
    admin_ctht_mail: Vue.$cookies.get("admin_ctht_mail"),
    admin_ctht_role: Vue.$cookies.get("admin_ctht_role"),
    admin_ctht_photo: Vue.$cookies.get("admin_ctht_photo"),
    users_ctht_token: Vue.$cookies.get("users_ctht_token"),
};

var vm = null;

vm = new Vue({
    router,
    render: h => h(App),
    i18n
}).$mount('#app');


router.beforeEach((to, from, next) => {
    axios.get(Vue.BASE_URL + "/utilisateur/getuserconfig").then(function () {
    });
    var user = {
        admin_ctht_id: Vue.$cookies.get("admin_ctht_id"),
        admin_ctht_login: Vue.$cookies.get("admin_ctht_login"),
        admin_ctht_nom: Vue.$cookies.get("admin_ctht_nom"),
        admin_ctht_mail: Vue.$cookies.get("admin_ctht_mail"),
        admin_ctht_role: Vue.$cookies.get("admin_ctht_role"),
        admin_ctht_photo: Vue.$cookies.get("admin_ctht_photo"),
        users_ctht_token: Vue.$cookies.get("users_ctht_token"),
    };
    vm.$emit('change-load', 'loading', user);
    if (!Tools.Valider(user.admin_ctht_id, user.users_ctht_token)) {
        // location.reload();
    } else {
        // next();
    }
    next();

})

router.afterEach(() => {
    var user = {
        admin_ctht_id: Vue.$cookies.get("admin_ctht_id"),
        admin_ctht_login: Vue.$cookies.get("admin_ctht_login"),
        admin_ctht_nom: Vue.$cookies.get("admin_ctht_nom"),
        admin_ctht_mail: Vue.$cookies.get("admin_ctht_mail"),
        admin_ctht_role: Vue.$cookies.get("admin_ctht_role"),
        admin_ctht_photo: Vue.$cookies.get("admin_ctht_photo"),
        users_ctht_token: Vue.$cookies.get("users_ctht_token"),
    };
    vm.$emit('change-load', 'loading', user);
    vm.$emit('change-load', 'loaded', user);
})
// console.log(user);
vm.$emit('change-load', 'loading', user);
vm.$emit('change-load', 'loaded', user);
// });
