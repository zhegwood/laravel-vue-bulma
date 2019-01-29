<template>
<div class="columns is-centered">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd">
        <h1 class="title is-1">Login</h1>
        <div class="panel">
            <div v-if="activation" class="notification is-success margin-bottom-1">
                <span>Activation Success!  You man now log in.</span>
            </div>
            <form @submit.prevent="login">
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Username" v-model="user.username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Password" v-model="user.password">
                    </div>
                </div>
                <div class="field is-clearfix">
                    <div class="control is-pulled-right">
                        <label class="checkbox">
                            <input type="checkbox" />
                            Remember Me
                        </label>
                    </div>
                </div>
                <div class="field is-clearfix">
                    <div class="control is-pulled-right">
                        <button class="button is-success" :disabled="processing">Login</button>
                    </div>
                </div>
            </form>
        </div>
        <p><a href="/register" class="margin-bottom-1">Register for an Account</a></p>
        <p><a href="/resend" class="margin-bottom-1">Resend Activation Email</a></p>
    </div>
</div>

</template>
<script>
export default {
    data() {
        return {
            user: {}
        }
    },
    computed: {
        activation() {
            let url = location.href,
                parts = url.split('?');
            if (!parts[1]) { return false; }
            parts = parts[1].split('=');
            if (parts.length === 2 && parts[0] === 'activate' && parts[1] === "true") {
                return true;
            }
            return false;
        },
        processing() {
            return this.$store.getters.processing;
        }
    },
    methods: {
        login() {
            if (!this.processing) {
                let params = {
                    'username': this.user.username,
                    'password': this.user.password,
                    'remember': this.user.remember
                }
                this.$store.dispatch("userLogin",params);
            }
        }
    },
    created() {
        //just in case something isn't quite right with the session store
        if (typeof Storage !== "undefined") {
            sessionStorage.clear();
        }
    }
}
</script>