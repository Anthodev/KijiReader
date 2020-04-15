<template>
    <v-row class="mt-7 mb-2 ml-1">
        <v-btn class="mr-2" color="warning" normal>Mark everything as read</v-btn>
        <v-select class="pa-0 col-3" :items="selectFilters" label="Show news since..." disabled solo dense hide-details></v-select>
    </v-row>
</template>

<script>
export default {
    data() {
        return {
            selectFilters: ['all', 'day', 'week', 'month']
        }
    }
}
</script>

<style>
    
</style>
