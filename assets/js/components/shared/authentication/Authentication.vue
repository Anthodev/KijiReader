<template>
    <div class="container divAuth">
        <div id="componentSignin" class="d-flex align-items-center justify-content-center">
            <router-view name="signinComponent"></router-view>
        </div>
        <div id="componentSignup" class="d-flex align-items-center justify-content-center">
            <router-view name="signupComponent"></router-view>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            authenticationComponent: 'appSignup'
        }
    },

    methods: {
    }
}
</script>

<style lang="scss" scoped>
.divAuth {
    margin-top: 50vh;
    display: flex;
    transform: translateY(-50%);
}

#componentSignup {
    margin: 0 auto;
}
</style>
