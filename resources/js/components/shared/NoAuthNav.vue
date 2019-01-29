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
            <a href="/register" class="navbar-item" :class="{'is-active':register_active}">Register</a>
            <a href="/login" class="navbar-item" :class="{'is-active':login_active}">Login</a>
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
        register_active() {
            let url = location.href;
            return url.indexOf('register') !== -1;
        },
        login_active() {
            let url = location.href;
            return url.indexOf('login') !== -1;
        }
    },
    methods: {
        toggleNav() {
            this.navbar_expanded = !this.navbar_expanded
        }
    }
};
</script>