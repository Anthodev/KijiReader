<template>
    <v-expansion-panel class="mx-auto newsCard" @click="openNews" outlined>
        <v-expansion-panel-header>
            <v-chip class="ml-n3" color="indigo"  small pill label>{{ news.feed_name }}</v-chip>
            <span class="col-10">{{ news.title }}</span>
            <span class="text-right">Il y a {{ news.dateDiff }}</span>
        </v-expansion-panel-header>
        <v-expansion-panel-content class="newsDesc">
            <a :href="news.url" target="_blank" rel="noopener noreferrer"><p class="title">{{ news.title }}</p></a>

            <p class="body-2" v-html="news.description">{{ news.description }}</p>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
export default {
    props: ['news'],

    data() {
        return {
            openState: false,
            attachReadMarker: true,
        }
    },

    watch: {
        openState: function() {
            return console.log('News opened ' + this.openState)
        }
    },

    methods: {
        openNews() {
            this.attachReadMarker = false
        },


    }
}
</script>

<style lang="scss" scoped>
::v-deep .v-chip__content {
    text-overflow: ellipsis;
}

.newsDesc {
    margin-top: 0.5rem;
}
</style>
