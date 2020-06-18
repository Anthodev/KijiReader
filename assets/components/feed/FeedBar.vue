<template>
  <v-row class="mt-7 mb-2 ml-1">
    <v-btn @click="markRead" :loading="loading" :disabled="disabled" class="mr-2" color="warning" normal>Mark everything as read</v-btn>
    <v-btn @click="switchHideStatus()" v-if="!currentHideStatus" class="mr-2" color="primary" normal>Hide read <v-icon right>mdi-eye-off</v-icon></v-btn>
    <v-btn @click="switchHideStatus()" v-else class="mr-2" color="primary" normal>Show read <v-icon right>mdi-eye</v-icon></v-btn>
    <!-- <v-select class="pa-0 col-3" :items="selectFilters" label="Show news since..." disabled solo dense hide-details></v-select> -->
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      selectFilters: ['all', 'day', 'week', 'month'],
      loading: false,
      disabled: false,
      hideStatus: false,
      currentHideStatus: false,
    }
  },

  computed: {
    id() {
      let id = this.$route.params.id

      if (id == undefined) id = 0

      return id
    },

    user() {
      this.hideStatus = this.$store.getters.user.settings.display_unread
      this.currentHideStatus = this.$store.getters.user.settings.display_unread
      return this.$store.getters.user
    }
  },

  methods: {
    markRead() {
      this.loading = true
      this.disabled = true

      this.$store.dispatch('SET_MARK_FEED_AS_READ', this.id).then(() => {
        this.loading = false
        this.disabled = false
      })
    },

    switchHideStatus() {
      this.currentHideStatus = !this.hideStatus
      this.$store.dispatch('FILTER_NEWSFEED', this.currentHideStatus).then(() => {
        $nuxt.refresh()
        this.hideStatus = !this.hideStatus
      })
    }
  }
}
</script>

<style>
    
</style>
