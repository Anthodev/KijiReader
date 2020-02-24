<template>
    <div class="col feedNews d-flex flex-wrap" :class="{readMarker: attachReadMarker}">
        <div class="row col-12 newsBand" @click="openNews">
            <div class="col-10">
                <span class="col-2 badge badge-name"></span>
                <span class="col newsTitle"></span>
            </div>
            <div class="col news-timestamp">
                <span>Il y a </span>
            </div>
        </div>

        <!-- <div class="row col-12" v-if="openState"> -->
        <slide-up-down class="row col-12 newsContent" :active="openState" :duration="500">
            <h1></h1>
            <div class="content">
                
            </div>
        </slide-up-down>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
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
        ...mapGetters({

        }),
        ...mapActions({

        }),

        openNews() {
            this.openState = !this.openState
            this.attachReadMarker = false
        }
    }
}
</script>

<style scoped>
.feedNews {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    border-radius: 0.5em;
    color: #F5E2C8;
    background-color: #121212;
    padding: 0.5em 0.25em 0.5em 0.25em;
    font-weight: bold;
    margin-bottom: 0.25em;
}

.newsBand {
    cursor: pointer;
}

.readMarker {
    border-left: 0.35em solid #55DDE0;
}

.badge-name {
    background-color: #33658A;
    text-align: left;
    text-overflow: ellipsis;
}

.newsTitle {
    text-overflow: ellipsis;
}

.news-timestamp {
    text-align: right;
    font-weight: lighter;
    font-size: 0.8em;
}

.newsContent {
    padding-left: 2rem;
}

.newsContent h1:first-child {
    margin: 0.5rem 0 1.5rem 0;
}

.content {
    text-align: left;
}
</style>
