<template>
    <!-- <div class="col feedNews d-flex flex-wrap" :class="{readMarker: attachReadMarker}">
        <div class="row col-12 newsBand" @click="openNews">
            <div class="col-10">
                <span class="col-2 badge badge-name">{{ news.feed_name }}</span>
                <span class="col newsTitle">{{ news.title }}</span>
            </div>
            <div class="col news-timestamp">
                <span>Il y a {{ news.date }}</span>
            </div>
        </div>
        
        <slide-up-down class="row col-12 newsContent" :active="openState" :duration="500">
            <h3>{{ news.title }}</h3>
            <hr/>
            <div class="content">
                {{ news.description }}
            </div>
        </slide-up-down>
    </div> -->
    <v-expansion-panel class="mx-auto newsCard" @click="openNews" outlined>
        <v-expansion-panel-header><span class="col-10">{{ news.title }}</span><span class="text-right">Il y a {{ news.date }}</span></v-expansion-panel-header>
        <v-expansion-panel-content class="newsDesc">{{ news.description }}</v-expansion-panel-content>
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
            this.openState = !this.openState
            this.attachReadMarker = false
        }
    }
}
</script>

<style lang="scss" scoped>
.newsDesc {
    margin-top: 0.5rem;
}
</style>
