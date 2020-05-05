<template>
  <v-list-item link>
    <v-list-item-action>
      <v-img :src="feed.logo" :lazy-src="feed.logo" alt="feed logo" contain />
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
      if (feed.logo == '') return false
      else return true
    }
  }
}
</script>
