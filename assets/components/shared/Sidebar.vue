<template>
  <div id="sidebar">
    <v-navigation-drawer v-model="drawer" width="12.5rem" app clipped>
      <v-list>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>All elements</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-settings</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Settings</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <nuxt-link to="/settings"><v-btn v-if="checkAuth" class="mb-2" color="primary" block>Settings</v-btn></nuxt-link>
          <v-btn v-if="checkAuth" @click.native="onLogout" color="error" block>Logout</v-btn>
        </div>
      </template>
    </v-navigation-drawer>
  </div>
</template>

<script>
import FeedAdd from './FeedAdd'

export default {
  computed: {
    checkAuth() {
      return this.$store.getters.isAuthenticated
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
        console.log(e)

        $store.dispatch('SET_LOADING_STATE', {
          loading: false,
          type: ''
        })
      })
    }
  },

  components: {
    appFeedAdd: FeedAdd
  },

  async mounted() {
    let self = this

    setInterval(function(){
      console.log('test interval')
    }, 15000)
  }
}
</script>
