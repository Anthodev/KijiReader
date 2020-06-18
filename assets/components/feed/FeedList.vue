<template>
    <div>
      <app-news-card v-for="(news, $index) in items" :key="$index" :news="news" />
      <infinite-loading v-if="items.length > 0" spinner="spiral" @infinite="loadMoreNews" />
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'

export default {
  data() {
    return {
      offset: 0,
      items: [],
      id: null,
      dataLoading: true
    }
  },

  computed: {
    filteredNewsfeed () {
      return this.$store.getters.filteredNewsfeed
    },
  },

  methods: {
    async loadMoreNews($state) {
      setTimeout(() => {
        const result = this.filteredNewsfeed.slice(this.offset, this.offset + 50)

        if (result.length > 0) {
          result.forEach((item) => this.items.push(item))
          this.offset += result.length
          $state.loaded()
        } else $state.complete()
      }, 1000)
    },
  },

  components: {
    appNewsCard: () => import('./NewsCard'),
    InfiniteLoading
  },

  async fetch() {
    this.items.length = 0

    while (this.items.length == 0 && this.filteredNewsfeed.length > 0) {
      this.items = this.filteredNewsfeed.slice(this.offset, this.offset + 50)
    }

    this.offset = this.items.length

    if (this.$route.params.id == undefined) this.id = this.$route.params.id
    else this.id = 0

    return result
  },
}
</script>
