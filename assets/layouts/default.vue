<template>
  <v-app id="app">
    <v-skeleton-loader
      class="my-auto mx-auto"
      :loading="loadingState.loading"
      transition-group="fade-transition"
      width="33%"
      :type="loadingState.type"
    >
      <v-content id="content">
          <app-header />
          <v-container class="fill-height ml-8" fluid>
            <app-sidebar />
            <Nuxt />
          </v-container>
          <app-footer />
      </v-content>
    </v-skeleton-loader>
  </v-app>
</template>

<script>
  import Header from '../components/shared/Header.vue'
  import Footer from '../components/shared/Footer.vue'
  import Sidebar from '../components/shared/Sidebar.vue'

  export default {
    name: 'KijiReader',

    props: {
        source: String,
    },

    data() {
      return {
        
      }
    },

    methods: {
        fetchUser() {
            this.$store.dispatch('FETCH_USER')
        }
    },

    computed: {
      auth () {
        return this.$store.getters.isAuthenticated
      },

      loadingState() {
        return this.$store.getters.loadingState
      }
    },

    components: {
      appHeader: Header,
      appFooter: Footer,
      appSidebar: Sidebar,
    },

    async mounted() {
      if (this.$store.getters.user == null) this.fetchUser()

      this.$store.dispatch('SET_LOADING_STATE', {
        loading: true,
        type: "card-heading, list-item@6, text"
      })
    }
  }
</script>

<style lang="stylus" scoped>
#content {
  padding-right: 64px !important;
}
</style>
