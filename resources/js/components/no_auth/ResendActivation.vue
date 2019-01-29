<template>
<div class="columns is-centered">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd">
        <h1 class="title is-1">Resend Activation</h1>
        <div class="panel">
            <div v-if="resend_success">
                <span>If your email address is found, another activation will be sent to {{ user.email_address }}.</span>
            </div>
            <div v-else>
                <resend-form @update-user="updateUser"></resend-form>
            </div>
        </div>
        <p><a href="/login" class="margin-bottom-1">Login</a></p>
        <p><a href="/register" class="margin-bottom-1">Register for an Account</a></p>
    </div>
</div>
</template>
<script>
import ResendForm from '../shared/ResendForm.vue'
export default {
    data() {
        return {
            user: {}
        }
    },
    components: {
        ResendForm
    },
    computed: {
        resend_success() {
            return this.$store.getters.resend_success;
        }
    },
    methods: {
        updateUser(user) {
            this.user = user;
        }
    }
}
</script>