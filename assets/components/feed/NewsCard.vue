<template>
  <v-card class="mb-1" :class="{ 'newsReadMarker': !attachReadMarker }">
    <v-card-subtitle class="col-12 d-inline-flex align-center" style="cursor: pointer" @click="showContent">
      <div class="col-1">
        <v-chip color="indigo" small pill label><span class="d-inline-block text-truncate">{{ news.feed.name }}</span></v-chip>
      </div>
      <div class="flex-grow-1">
        <span class="col-12 ml-3">{{ news.story.title }}</span>
      </div>
      <div>
        <span class="text-right align-self-end">{{ dateAgo }} ago</span>
        <v-btn icon><v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon></v-btn>
      </div>
    </v-card-subtitle>
    <v-expand-transition>
      <div v-show="show">
        <v-divider class="my-n2"/>

        <v-card-text><a class="title" :href="news.story.url" target="_blank" rel="noopener noreferrer">{{ news.story.title }}</a></v-card-text>

        <v-card-text v-html="news.story.content"/>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script>
import { formatDistanceToNow, parseISO } from 'date-fns'

export default {
  props: ['news'],

  data() {
    return {
      show: false,
      attachReadMarker: this.news.read_status,
    }
  },

  computed: {
    dateAgo() {
      return formatDistanceToNow(parseISO(this.news.story.date))
    },
  },

  methods: {
    showContent() {
      this.show = !this.show

      if (!this.attachReadMarker) {
        this.attachReadMarker = !this.attachReadMarker
        this.$store.dispatch('SET_MARK_AS_READ', this.news.id)
          .then(() => {
            if (this.$store.getters.serverError == '') {
                this.$store.dispatch('DELETE_SERVER_ERROR')
                this.$store.dispatch('FETCH_UNREAD_COUNT')
              } else {
                this.attachReadMarker = !this.attachReadMarker
                this.$toast.error('Error marking the news as read.')
                this.$store.dispatch('DELETE_SERVER_ERROR')
              }
            })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.newsReadMarker {
  box-shadow: -5px 0px 0px 0px darkred !important;
}
</style>
