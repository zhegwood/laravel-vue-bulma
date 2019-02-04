<template>
<div class="columns is-centered">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd">
        <h1 class="title is-1">User Settings</h1>
        <div class="panel">
            <form @submit.prevent="saveUser">
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
                <div class="field is-clearfix">
                    <div class="control is-pulled-right">
                        <button class="button is-success" type="submit" :disabled="processing">Save Settings</button>
                        <a href="javascript: void(0);" class="button is-danger" @click="inactivateUser">Inactivate Account</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" :class="{'is-active': show_delete_modal}">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box is-clearfix">
                <p class="margin-bottom-1">Are you sure you want to inactivate your account?</p>
                <div class="is-pulled-right">
                    <a href="javascript: void(0);" class="button is-danger" :disabled="processing" @click="confirmInactivate">Yes, Inactivate</a>
                    <a href="javascript: void(0);" class="button is-default" :disabled="processing" @click="show_delete_modal = false;">Close</a>
                </div>
            </div>
            <!-- Any other Bulma elements you want -->
        </div>
        <button class="modal-close is-large" aria-label="close" :disabled="processing" @click="show_delete_modal = false"></button>
    </div>
</div>
</template>
<script>
export default {
    data() {
        return {
            show_delete_modal: false,
            user: {}
        }
    },
    computed: {
        auth_user() {
            return this.$store.getters.auth_user;
        },
        processing() {
            return this.$store.getters.processing;
        }
    },
    watch: {
        auth_user(new_val) {
            if (new_val) {
                this.populateUser();
            }
        }
    },
    methods: {
        confirmInactivate() {
            this.$store.dispatch("inactivate");
        },
        inactivateUser() {
            this.show_delete_modal = true;
        },
        populateUser() {
            let auth_user = this.auth_user
            this.user = {
                first_name: auth_user.first_name,
                last_name: auth_user.last_name,
                email_address: auth_user.email_address
            }
        },
        saveUser() {
            if (this.validUser() && !this.processing) {
                let u = this.user;
                let params = {
                    first_name: u.first_name,
                    last_name: u.last_name,
                    email_address: u.email_address,
                    agree: true
                };
                if (u.password && u.password !== '') {
                    params.password = u.password
                    params.password_confirm = u.password_confirm
                }
                this.$store.dispatch('userSave',params);
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
            if (u.password && u.password !== '') {
                if (u.password !== u.password_confirm) {
                    this.$store.commit('global_error','Passwords do not match.');
                    return false;
                }
            }
            return true;
        }
    },
    created(){
        if (this.auth_user) {
            this.populateUser();
        }
    }
}
</script>