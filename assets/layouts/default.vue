<template>
  <v-app id="app" v-if="auth">
    <v-skeleton-loader
      class="my-auto mx-auto"
      :loading="loadingState.loading"
      transition-group="fade-transition"
      width="50%"
      :type="loadingState.type"
    >
      <v-main>
        <v-container id="content">
            <app-header />
            <app-sidebar />
            <v-row class="col-12 ml-8" fluid>
              <Nuxt />
            </v-row>
            <app-footer />
        </v-container>
      </v-main>
    </v-skeleton-loader>
  </v-app>
  <v-app v-else>
    <v-skeleton-loader
      class="my-auto mx-auto"
      loading=true
      transition-group="fade-transition"
      width="33%"
      type="card-heading, list-item-avatar@2, actions"
    />
  </v-app>
</template>

<script>
export default {
  name: 'KijiReader',

  props: {
    source: String,
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
    appHeader: () => import('../components/shared/Header.vue'),
    appFooter: () => import('../components/shared/Footer.vue'),
    appSidebar: () => import('../components/shared/sidebar/Sidebar.vue'),
  },

  async mounted() {
    if (this.$store.getters.user == null) this.fetchUser()
  }
}
</script>

<style lang="stylus" scoped>
#content {
  padding-right: 64px !important;
  width: 100%;
}
</style>
