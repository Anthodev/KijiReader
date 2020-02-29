<template>
    <div class="container divAuth">
        <div id="componentSignin" class="d-flex align-items-center justify-content-center" v-if="getUsersCount > 0">
            <router-view name="signinComponent"></router-view>
        </div>
        <div id="componentSignup" class="d-flex align-items-center justify-content-center" v-else>
            <router-view name="signupComponent"></router-view>
        </div>
    </div>
</template>

<script>
import Signup from './Signup'
import Signin from './Signin'

export default {
    data() {
        return {
            authenticationComponent: 'appSignup'
        }
    },

    methods: {
    },

    computed: {
        getUsersCount () {
            if (this.$store.usersCount > 0 && this.$router.path !== '/signin') this.$router.replace('/signin')
            else if (this.$store.usersCount == 0 && this.$router.path !== '/signup') this.$router.replace('/signup')

            return this.$store.usersCount
        }
    },

    components: {
        appSignup: Signup,
        appSignin: Signin
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
