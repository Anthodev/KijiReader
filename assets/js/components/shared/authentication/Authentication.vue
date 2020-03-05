<template>
    <v-app>
        <v-content>
            <v-container class="fill-height">
                <router-view name="signinComponent"></router-view>
                <router-view name="signupComponent"></router-view>
            </v-container>
        </v-content>
    </v-app>
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
