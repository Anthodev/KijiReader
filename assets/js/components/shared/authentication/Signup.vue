<template>
    <div id="signup">
        <form @submit.prevent="onSubmit">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control" v-model="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" v-model="password">
                <small id="passwordHelp" class="form-text text-muted">Alphanumerical and 8 characters minimum</small>
            </div>
            <div class="form-group">
                <label for="passwordConfirm">Confirm password:</label>
                <input type="password" id="passwordConfirm" class="form-control" v-model="passwordConfirm">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" class="form-control" v-model="email">
            </div>

            <button type="submit" class="btn btn-primary">Create account</button>
        </form>
    </div>
</template>

<script>
export default {
    
}
</script>

<style lang="less" scoped>

</style>
