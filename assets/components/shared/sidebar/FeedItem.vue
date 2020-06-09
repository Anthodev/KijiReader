<template>
  <v-list-item link>

    <v-menu open-on-hover offset-x>
      <template v-slot:activator="{ on }">

        <v-row v-on="on">
          <v-list-item-action>
            <template v-if="feedIcon != ''">
              <v-img :src="feedIcon" alt="feed logo" class="feedLogo" contain />
            </template>
            <template v-else>
              <v-icon>mdi-rss-box</v-icon>
            </template>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-if="feed.unreadCount > 0">
              <v-badge color="red" :content="feed.unreadCount" :value="feed.unreadCount" inline><span class="d-inline-block text-truncate" style="max-width: 5.0rem">{{ feed.name }}</span></v-badge>
            </v-list-item-title>
            <v-list-item-title v-else>{{ feed.name }}</v-list-item-title>
          </v-list-item-content>
        </v-row>
      </template>

      <v-toolbar collapse>
        <v-btn icon>
          <v-icon>mdi-eye</v-icon>
        </v-btn>

        <v-btn @click="showOverlay = !showOverlay" icon>
          <v-icon>mdi-trash-can</v-icon>
        </v-btn>
      </v-toolbar>

    </v-menu>

    <v-overlay :value="showOverlay">
      <v-card>
        <v-card-title class="headline">Delete the feed?</v-card-title>
        <v-card-text>Are you sure to delete this feed: {{ feed.name }}</v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="error" @click="showOverlay = false">CANCEL</v-btn>
          <v-btn color="success" @click="deleteFeed">CONFIRM</v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-overlay>

  </v-list-item>
</template>

<script>
export default {
  props: ['feed', 'unreadCount'],

  data() {
    return {
      showOverlay: false
    }
  },

  computed: {
    feeds() {
      return this.$store.getters.feeds
    },

    feedIcon() {
      if (this.feed.logo == '') return ''
      else return this.feed.logo
    }
  },

  methods: {
    deleteFeed() {
      this.$store.dispatch('DELETE_FEED', this.feed.id)
      this.showOverlay = !this.showOverlay
    }
  }
}
</script>

<style lang="scss" scoped>
.feedLogo {
  max-width: 2em;
  max-height: 1em;
}
</style>
