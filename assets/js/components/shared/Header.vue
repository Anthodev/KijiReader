<template>
    <div id="header" class="fixed-top">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation">
            <router-link to="/" class="navbar-brand">KijiReader</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarHeader">
                <ul class="navbar-nav mr-auto"></ul>
                <ul class="navbar-nav my-2 my-lg-0">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account</a>
                        <div class="dropdown-menu dropdown-menu-right text-right">
                            <router-link to="/feed" class="dropdown-item">Profile</router-link>
                            <router-link to="/feed" class="dropdown-item">Settings</router-link>
                            <router-link to="/feed" class="dropdown-item">Manage subscriptions</router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
    export default {
        
    }
</script>

<style scoped>
    
</style>
