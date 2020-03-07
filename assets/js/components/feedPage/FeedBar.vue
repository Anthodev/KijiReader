<template>
    <v-row class="col-4">
        <v-btn color="warning" large>Mark everything as read</v-btn>
        <v-spacer></v-spacer>
        <v-select class="pa-0" :items="selectFilters" label="Show news since..." disabled solo dense hide-details></v-select>
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
