<template>
    <div id="signin">
        <form @submit.prevent="onSubmit">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control" v-model="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" v-model="password">
            </div>

            <button type="submit" class="btn btn-primary">Create account</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            username: '',
            password: ''
        }
    }
}
</script>

<style lang="scss" scoped>
#signin {
    width: 100%;
}
</style>
