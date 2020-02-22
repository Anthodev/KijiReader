<template>
    <div id="feedList">
        
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            newsList = this.$store.state.news,
        }
    },

    methods: {
        ...mapGetters(news, {

        }),
        ...mapActions(news, {

        })
    }
}
</script>
