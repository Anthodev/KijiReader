<template>
  <div class="container-fluid">
    <div v-if="auth">
      <app-header></app-header>
      <app-main></app-main>
      <app-footer></app-footer>
    </div>
    <div v-else>
      <app-authentication></app-authentication>
    </div>
  </div>
</template>

<script>
  import Header from './components/shared/Header.vue'
  import Footer from './components/shared/Footer.vue'
  import Main from './components/Main.vue'

  import Authentication from './components/shared/authentication/Authentication'

  export default {
    name: 'App',

    data() {
      return {
        
      }
    },

    computed: {
      auth () {
        return this.$store.getters.isAuthenticated
      }
    },

    components: {
      appHeader: Header,
      appFooter: Footer,
      appMain: Main,
      appAuthentication: Authentication
    }
  }
</script>

<style lang="scss">
  $font-color: #f5e2c8;
  $bg-color: #2f2f2f;

  body {
    background-color: $bg-color;
    color: $font-color;
  }
</style>
