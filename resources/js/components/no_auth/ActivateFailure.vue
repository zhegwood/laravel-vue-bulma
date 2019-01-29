<template>
<div class="columns is-centered">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd">
        <h1 class="title is-1">Activation Failure</h1>
        <div class="panel">
            <div v-if="resend_success">
                <span>If your email address is found, you will receive another activation link at {{ user.email_address }}.</span>
            </div>
            <div v-else>
                <p class="margin-bottom-1">For some reason, activation has failed. Please enter your email address below to re-send the activation email.</p>
                <resend-form @update-user="updateUser"></resend-form>
            </div>
        </div>
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