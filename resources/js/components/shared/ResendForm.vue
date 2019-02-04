<template>
<form @submit.prevent="resend">
    <div class="field">
        <label class="label">Email Address</label>
        <div class="control">
            <input class="input" type="text" placeholder="Email Address" v-model="user.email_address" @input="updateUser">
        </div>
    </div>
    <div class="field is-clearfix">
        <div class="control is-pulled-right">
            <button type="submit" class="button is-success" :disabled="processing">Resend Activation Email</button>
        </div>
    </div>
</form>
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
        }
    },
    methods: {
        resend() {
            if (this.validUser() && !this.processing) {
                let params = {
                    'email_address': this.user.email_address
                };
                this.$store.dispatch('activationResend',params);
            }
        },
        updateUser() {
            this.$emit('update-user',this.user);
        },
        validUser() {
            let u = this.user;
            this.$store.commit('global_error','');
            if (!u.email_address || u.email_address === '') {
                this.$store.commit('global_error','Email Address is required.');
                return false;
            }
            return true;
        }
    }
}
</script>