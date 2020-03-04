<template>
    <v-row>
        <v-col cols="8">
            <v-toolbar>
                <template>
                    <v-btn x-large>Mark everything as read</v-btn>
                    <v-spacer></v-spacer>
                    <v-select class="pa-0" :items="selectFilters" label="Show news since..." outlined hide-details></v-select>
                </template>
            </v-toolbar>
        </v-col>
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
