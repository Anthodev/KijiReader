<template>
    <div id="sidebar">
        <div id="addFlux" class="form-row">
            <div class="col">
                <input type="text" name="inputAddFeed" id="inputAddFeed" class="form-control" placeholder="Add a feed" v-model="inputFeed">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary" @click="submit">+</button>
            </div>
        </div>
        <hr>
        <div id="sidebarMenu">
            <div class="menuElement"><span><font-awesome-icon :icon="['fas', 'home']" /> Home</span></div>
            <div class="menuElement"><span><font-awesome-icon :icon="['fas', 'star']" /> Starred</span></div>
            <div class="menuElement"><span><font-awesome-icon :icon="['fas', 'bookmark']" /> Bookmarked</span></div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            inputFeed: ''
        };
    },

    methods: {
        submit() {
            
        }
    }
}
</script>

<style scoped>
#sidebar {
    height: 78vh;
    padding: 1rem 2rem 1rem 1rem;
    box-shadow: 0 0 1em #2f2f2f;
    border-radius: 1em;
}

.menuElement {
    padding-left: 1rem;
}
</style>
