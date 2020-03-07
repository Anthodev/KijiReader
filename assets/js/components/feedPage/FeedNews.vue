<template>
    <v-expansion-panel class="mx-auto newsCard" outlined>
        <v-expansion-panel-header :class="{ 'newsReadMarker': !attachReadMarker }" @click="openNews">
            <v-chip class="ml-n3" color="indigo"  small pill label>{{ news.feed.name }}</v-chip>
            <span class="col-10">{{ news.story.title }}</span>
            <span class="text-right">{{ dateAgo }} ago</span>
        </v-expansion-panel-header>
        <v-expansion-panel-content class="newsDesc">
            <a :href="news.story.url" target="_blank" rel="noopener noreferrer"><p class="title">{{ news.story.title }}</p></a>

            <p class="body-2 story-content" v-html="news.story.content"></p>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
import { formatDistanceToNow } from 'date-fns'

export default {
    props: ['news'],

    data() {
        return {
            openState: false,
            attachReadMarker: this.news.readStatus
        }
    },

    computed: {
        dateAgo() {
            return formatDistanceToNow(new Date(this.news.story.date.timestamp * 1000))
        },
    },

    watch: {
        openState: function() {
            return console.log('News opened ' + this.openState)
        }
    },

    methods: {
        openNews() {
            this.attachReadMarker = true
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
