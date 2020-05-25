<template>
  <v-list-item link>
    <v-list-item-action>
      <template v-if="feedIcon != ''">
        <v-img :src="feedIcon" alt="feed logo" class="feedLogo" contain />
      </template>
      <template v-else>
        <v-icon>mdi-rss-box</v-icon>
      </template>
      <!-- <v-img :src="feedIcon" alt="feed logo" contain /> -->
    </v-list-item-action>
    <v-list-item-content>
      <v-list-item-title v-if="feed.unreadCount > 0">
        <v-badge color="red" :content="feed.unreadCount" :value="feed.unreadCount" inline><span class="d-inline-block text-truncate" style="max-width: 5.0rem">{{ feed.name }}</span></v-badge>
      </v-list-item-title>
      <v-list-item-title v-else>{{ feed.name }}</v-list-item-title>
    </v-list-item-content>
  </v-list-item>
</template>

<script>
export default {
  props: ['feed', 'unreadCount'],

  computed: {
    feeds() {
      return this.$store.getters.feeds
    },

    feedIcon() {
      if (this.feed.logo == '') return ''
      else return this.feed.logo
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
