<template>
  <div id="sidebar">
    <v-navigation-drawer v-model="drawer" width="13rem" app clipped>
      <v-list dense>
        <v-list-item @click="refreshNewsfeed">
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-if="unreadAllCount > 0">
              <v-badge color="red" :content="unreadAllCount" :value="unreadAllCount" offset-x="2" offset-y="10">All elements</v-badge>
            </v-list-item-title>
            <v-list-item-title v-else>All elements</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <app-feed-item v-for="(feedItem, i) in unreadList" :key="i" :feed="feedItem" />
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <nuxt-link to="/settings"><v-btn v-if="checkAuth" class="mb-2" color="primary" block><v-icon left>mdi-settings</v-icon> Settings</v-btn></nuxt-link>
          <v-btn v-if="checkAuth" @click.native="onLogout" color="error" block><v-icon left>mdi-exit-to-app</v-icon>Logout</v-btn>
        </div>
      </template>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  computed: {
    checkAuth() {
      return this.$store.getters.isAuthenticated
    },

    unreadAllCount() {
      return this.$store.getters.unreadAllCount
    },

    unreadList() {
      return this.$store.getters.unreadFeedList
    },

    drawer: {
      get () {
        return this.$store.getters.drawer
      },

      set() {}
    }
  },

  methods: {
    onLogout() {
      this.$store.dispatch('SET_LOADING_STATE', {
        loading: true,
        type: "card-heading, list-item@6, text"
      })

      this.$store.dispatch('LOGOUT').catch((e) => {
        this.$store.dispatch('SET_LOADING_STATE', {
          loading: false,
          type: ''
        })
      })
    },

    startFetchUnreadCount: function() {
      const self = this

      setInterval(() => {
        this.$store.dispatch('FETCH_UNREAD_COUNT')
          .then(() => {
            if (this.$store.getters.serverError != '') {
              this.$store.dispatch('DELETE_SERVER_ERROR')
            }
          })
      }, 300000)
    },

    refreshNewsfeed() {
      this.$store.dispatch('FETCH_NEWSFEED')
    }
  },

  components: {
    appFeedItem: () => import('./FeedItem')
  },

  async mounted() {
    this.startFetchUnreadCount()
  }
}
</script>
