
window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

axios.interceptors.request.use(config => {
    config.send_error = config[0];
    return config;
});

import store from './stores/store.js'

axios.interceptors.response.use(
    response => {
        if (response.data.success || response.config.send_error) {
            return response.data;
        } else {
            store.commit("global_error", response.data.error);
        }
    },
    error => {
        let error_text = "";
        if (error.response) {
            let response = error.response;
            // The request was made and the server responded with a status code that falls out of the range of 2xx
            if (response.status == 302) {
                store.dispatch("logout", true);
                return;
            }
            error_text =
                response.status +
                ": " +
                response.data.exception +
                "<br/>file: " +
                response.data.file +
                "<br/>line: " +
                response.data.line +
                "<br/>message: " +
                (response.data.message ? response.data.message : "None");
        } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            let request = error.request;
            console.log("error2 error.request", error, error.request);
        } else {
            // Something happened in setting up the request that triggered an Error
            console.log("some other error", error);
            error_text = error.message;
        }
        store.commit("global_error", error_text);
    }
);
