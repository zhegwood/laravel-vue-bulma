import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);
export default new Vuex.Store({
    strict: true,
    state: {
        auth_user: null,
        auth_user_loading: true,
        global_error: "",
        processing: false,
        register_success: false,
        resend_success: false,
        tos: null
    },
    getters: {
        auth_user(state) {
            return state.auth_user;
        },
        auth_user_loading(state) {
            return state.auth_user_loading;
        },
        global_error(state) {
            return state.global_error;
        },
        processing(state) {
            return state.processing;
        },
        register_success(state) {
            return state.register_success;
        },
        resend_success(state) {
            return state.resend_success;
        },
        tos(state) {
            return state.tos;
        }
    },
    mutations: {
        auth_user(state, data) {
            state.auth_user = data;
        },
        auth_user_loading(state, data) {
            state.auth_user_loading = data;
        },
        global_error(state, data) {
            state.global_error = data;
        },
        processing(state, data) {
            state.processing = data;
        },
        register_success(state, data) {
            state.register_success = data;
        },
        resend_success(state, data) {
            state.resend_success = data;
        },
        tos(state, data) {
            state.tos = data;
        }
    },
    actions: {
        activationResend(context,params) {
            context.commit("global_error","");
            context.commit("processing",true);
            axios.post('/api/activation/resend',params,true).then(
                (response) => {
                    if (response.success) {
                        context.commit('resend_success',true);
                        context.commit("processing",false);
                    } else {
                        context.commit('global_error',response.error);
                        context.commit("processing",false);
                    }
                }
            );
        },
        authUser(context) {
            context.commit("auth_user_loading", true);
            if (typeof Storage !== "undefined") {
                if (sessionStorage.getItem("auth_user")) {
                    context.commit(
                        "auth_user",
                        JSON.parse(sessionStorage.getItem("auth_user"))
                    );
                    context.commit("auth_user_loading", false);
                } else {
                    axios.get("/api/auth-user/get",true).then(response => {
                        if (response.success) {
                            if (response.data.auth_user) {
                                sessionStorage.setItem(
                                    "auth_user",
                                    JSON.stringify(response.data.auth_user)
                                );
                                context.commit("auth_user", response.data.auth_user);
                            }
                        } else {
                            context.commit("global_error", response.error);
                        }
                        context.commit("auth_user_loading", false);
                    });
                }
            } else {
                axios.get("/api/auth-user/get").then(response => {
                    context.commit("auth_user", response.data.auth_user);
                    context.commit("auth_user_loading", false);
                });
            }
        },
        inactivate(context,params) {
            context.commit("processing", true);
            context.commit("global_error", "");
            axios.get("/api/user/inactivate").then(response => {
                if (response.success) {
                    if (typeof Storage !== "undefined") {
                        sessionStorage.clear();
                    }
                    location.href = "/";
                }
            });
        },
        register(context,params) {
            context.commit("register_success", false);
            context.commit("processing", true);
            context.commit("global_error", "");
            axios.post("/api/user/register",params,true).then(
                response => {
                    if (response.success) {
                        context.commit("register_success", true);
                        context.commit("processing", false);
                    } else {
                        context.commit("global_error", response.error);
                        context.commit("processing", false);
                    }
                }
            );
        },
        siteTos(context, params) {
            context.commit("tos", null);
            context.commit("global_error", "");
            axios.post("/api/tos/get",params).then(
                response => {
                    if (response.success) {
                        context.commit("tos", response.data.tos);
                    } else {
                        context.commit("global_error", response.error);
                    }
                }
            );
        },
        userExists(context,params) {
            context.commit("global_error", "");
            axios.post("/api/user/exists",params);
        },
        userLogin(context,params) {
            context.commit("global_error","");
            context.commit("processing",true);
            axios.post('/api/user/login',params,true).then(
                (response) => {
                    if (response.success) {
                        location.href = '/';
                    } else {
                        context.commit('global_error',response.error);
                        context.commit("processing",false);
                    }
                }
            );
        },
        userLogout(context) {
            context.commit("global_error","");
            axios.get('/api/user/logout',true).then(
                (response) => {
                    if (response.success) {
                        if (typeof Storage !== "undefined") {
                            sessionStorage.removeItem('auth_user');
                        }
                        location.href = '/';
                    } else {
                        context.commit('global_error',response.error);
                    }
                }
            );
        },
        userSave(context,params) {
            context.commit("global_error","");
            context.commit("processing",true);
            axios.post('/api/user/save',params,true).then(
                (response) => {
                    if (response.success) {
                        if (typeof Storage !== "undefined") {
                            sessionStorage.removeItem('auth_user');
                        }
                        location.reload();
                    } else {
                        context.commit('global_error',response.error);
                        context.commit("processing",false);
                    }
                }
            );
        }
    }
});
