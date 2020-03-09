<template>
  <v-app id="app">
    <v-content>
        <app-header />
        <app-content>
          <Nuxt />
        </app-content>
        <app-footer />
    </v-content>
  </v-app>
</template>

<script>
  import Header from '../components/shared/Header.vue'
  import Footer from '../components/shared/Footer.vue'
  import Content from '../components/shared/Content.vue'

  export default {
    name: 'KijiReader',

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
      appContent: Content
    },

    created () {
        this.$vuetify.theme.dark = true
    },
  }
</script>
