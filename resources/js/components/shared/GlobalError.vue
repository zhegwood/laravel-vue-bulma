<template>
<div class="global-error columns is-marginless is-centered" v-if="global_error !== ''">
    <div class="column is-12-mobile is-7-tablet is-6-desktop is-5-widescreen is-4-fullhd is-paddingless">
        <div class="notification is-danger">
            <button class="delete" @click="clearError"></button>
            <span v-html="global_error"></span>
        </div>
    </div>
</div>
</template>
<script>
export default {
    computed: {
        global_error() {
            return this.$store.getters.global_error;
        }
    },
    methods: {
        clearError() {
            this.$store.commit("global_error","");
        }
    }
}
</script>