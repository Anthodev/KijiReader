<template>
    <v-expansion-panel class="mx-auto newsCard mb-1" outlined>
        <v-expansion-panel-header :class="{ 'newsReadMarker': !attachReadMarker }" @click="showContent">
            <v-chip class="col-1 ma-n3" color="indigo"  small pill label>{{ news.feed.name }}</v-chip>
            <span class="col-9">{{ news.story.title }}</span>
            <span class="text-right">{{ dateAgo }} ago</span>
        </v-expansion-panel-header>
        <v-expansion-panel-content class="newsDesc">
            <a :href="news.story.url" target="_blank" rel="noopener noreferrer"><p class="title">{{ news.story.title }}</p></a>

            <p class="body-2 story-content" v-html="news.story.content"></p>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
import { formatDistanceToNow, parseISO } from 'date-fns'

export default {
    props: ['news'],

    data() {
        return {
            attachReadMarker: this.news.read_status,
        }
    },

    computed: {
        dateAgo() {
            return formatDistanceToNow(parseISO(this.news.story.date))
        }
    },

    methods: {
        showContent() {
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
::v-deep .v-chip__content {
    text-overflow: ellipsis;
}

.newsReadMarker {
    border-left: 0.5em solid darkred;
}

.newsDesc {
    margin-top: 0.25rem;
}

.story-content {
    white-space: pre-line;
}
</style>
