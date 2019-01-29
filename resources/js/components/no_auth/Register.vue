<template>
<div class="columns is-centered">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd">
        <h1 class="title is-1">Register</h1>
        <div class="panel">
            <p v-if="register_success"><strong>Registration Success!</strong><br/>You will receive and email with a link to validate your account. Once you click on that link, you will be able to log in.</p>
            <form @submit.prevent="register" v-else>
                <div class="field">
                    <label class="label">First Name</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="First Name" v-model="user.first_name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Last Name</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Last Name" v-model="user.last_name">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email Address</label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Email Address" v-model="user.email_address">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Username <small>(can be your email address)</small></label>
                    <div class="control">
                        <input class="input" type="text" placeholder="Username" @blur="userExists" v-model="user.username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Password" v-model="user.password">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirm Password</label>
                    <div class="control">
                        <input class="input" type="password" placeholder="Confirm Password" v-model="user.password_confirm">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Terms of Service</label>
                    <div class="terms-of-service panel">
                        <div v-html="tos"></div>
                    </div>
                </div>
                <div class="field is-clearfix">
                    <div class="control is-pulled-right">
                        <label class="checkbox">
                            <input type="checkbox" v-model="user.agree" />
                            I agree to the Terms of Service
                        </label>
                    </div>
                </div>
                <div class="field is-clearfix">
                    <div class="control is-pulled-right">
                        <button class="button is-success" :disabled="processing">Register</button>
                    </div>
                </div>
            </form>
        </div>
        <p><a href="/login" class="margin-bottom-1">Login</a></p>
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
        processing() {
            return this.$store.getters.processing;
        },
        register_success() {
            return this.$store.getters.register_success;
        },
        tos() {
            return this.$store.getters.tos;
        }
    },
    methods: {
        register() {
            if (this.validUser() && !this.processing) {
                this.$store.dispatch("register",this.user);
            }
        },
        userExists() {
            if (this.user.username && this.user.username !== "") {
                let params = {
                    'username': this.user.username
                };
                this.$store.dispatch("userExists",params);
            }
        },
        validUser() {
            let u = this.user;
            this.$store.commit('global_error','');
            if (!u.first_name || u.first_name === '') {
                this.$store.commit('global_error','First Name is required.');
                return false;
            }
            if (!u.last_name || u.last_name === '') {
                this.$store.commit('global_error','Last Name is required.');
                return false;
            }
            if (!u.email_address || u.email_address === '') {
                this.$store.commit('global_error','Email Address is required.');
                return false;
            }
            if (!u.username || u.username === '') {
                this.$store.commit('global_error','Username is required.');
                return false;
            }
            if (!u.password || u.password === '') {
                this.$store.commit('global_error','Password is required.');
                return false;
            }
            if (u.password !== u.password_confirm) {
                this.$store.commit('global_error','Passwords do not match.');
                return false;
            }
            if (!u.agree) {
                this.$store.commit('global_error','You must agree to the terms of service.');
                return false;
            }
            return true;
        }
    },
    created() {
        let params = {
            tos_type: 'site_terms'
        };
        this.$store.dispatch('siteTos',params);
    }
}
</script>