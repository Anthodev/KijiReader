<template>
  <v-app id="app" v-if="auth">
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
    appSidebar: () => import('../components/shared/Sidebar.vue'),
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
