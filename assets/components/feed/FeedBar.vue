<template>
    <v-row class="mt-7 mb-2 ml-1">
        <v-btn @click="markRead" :loading="loading" :disabled="disabled" class="mr-2" color="warning" normal>Mark everything as read</v-btn>
        <v-select class="pa-0 col-3" :items="selectFilters" label="Show news since..." disabled solo dense hide-details></v-select>
    </v-row>
</template>

<script>
export default {
    data() {
        return {
            selectFilters: ['all', 'day', 'week', 'month'],
            loading: false,
            disabled: false
        }
    },

    computed: {
        id() {
            let id = this.$route.params.id

            if (id == undefined) id = 0

            return id
        }
    },

    methods: {
        markRead() {
            this.loading = true
            this.disabled = true

            this.$store.dispatch('SET_MARK_FEED_AS_READ', this.id).then(() => {
                this.loading = false
                this.disabled = false

                this.$store.dispatch('SET_REFRESH_STATUS', true)
            })
        }
    }
}
</script>

<style>
    
</style>
