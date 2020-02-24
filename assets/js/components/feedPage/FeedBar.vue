<template>
    <div id="feedBar" class="form-inline row mb-2">
        <button class="btn btn-primary mr-sm-2">Mark everything as read</button>
        <select id="feedFilters" name="feedFilters" class="form-control">
            <option v-for="(filter, i) in selectFilters" :value="filter" :key="i">{{ filter }}</option>
        </select>
    </div>
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
