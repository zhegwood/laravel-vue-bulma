
window.Vue = require("vue");

require("./axios");

import store from './stores/store.js'

import AuthNav from "./components/shared/AuthNav.vue";
import GlobalError from "./components/shared/GlobalError.vue";
import NoAuthNav from "./components/shared/NoAuthNav.vue";

import ActivateFailure from './components/no_auth/ActivateFailure.vue';
import AuthHome from './components/auth/AuthHome.vue';
import Login from './components/no_auth/Login.vue';
import Register from './components/no_auth/Register.vue';
import ResendActivation from './components/no_auth/ResendActivation.vue';
import UserSettings from './components/auth/UserSettings.vue';

const app = new Vue({
    el: "#app",
    components: {
        AuthHome,
        AuthNav,
        GlobalError,
        NoAuthNav,
        ActivateFailure,
        Login,
        Register,
        ResendActivation,
        UserSettings
    },
    store,
    created() {
        this.$store.dispatch('authUser');
    }
});
