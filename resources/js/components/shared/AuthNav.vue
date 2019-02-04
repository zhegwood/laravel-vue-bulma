<template>
<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="https://github.com/zhegwood/laravel-vue-bulma">
            Laravel-Vue-Bulma
        </a>
        <a role="button" class="navbar-burger burger" :class="{'is-active':navbar_expanded}" aria-label="menu" aria-expanded="false" data-target="NavBar" @click="toggleNav">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div id="NavBar" class="navbar-menu" :class="{'is-active':navbar_expanded}">
        <div class="navbar-end">
            <div class="navbar-item has-dropdown is-hoverable" v-if="auth_user">
                <a class="navbar-link">Hello {{auth_user.first_name}}</a>
                <div class="navbar-dropdown">
                    <a class="navbar-item" href="/user-settings">User Settings</a>
                    <a href="javascript:void(0);" class="navbar-item" @click="logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>
</template>
<script>
export default {
    data() {
        return {
            navbar_expanded: false
        }
    },
    computed: {
        auth_user() {
            return this.$store.getters.auth_user;
        }
    },
    methods: {
        logout() {
            this.$store.dispatch('userLogout');
        },
        toggleNav() {
            this.navbar_expanded = !this.navbar_expanded
        }
    }
};
</script>