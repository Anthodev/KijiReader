<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
                <v-toolbar color="indigo" dark flat>
                    <v-toolbar-title>KijiReader - Login</v-toolbar-title>
                    <v-spacer />
                </v-toolbar>
                <v-card-text>
                    <v-form id="signinForm" @keyup.native.enter="onSubmit" @submit.prevent="onSubmit">
                        <v-text-field id="username" label="Login" name="username" prepend-icon="person" type="text" v-model="username" @blur="$v.username.$touch()" :error-messages="usernameErrors" placeholder="Enter your username" required autofocus></v-text-field>

                        <v-text-field id="login-password" label="Password" name="password" prepend-icon="lock" type="password" v-model="password" @blur="$v.password.$touch()" :error-messages="passwordErrors" placeholder="Enter your password"></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn form="signinForm" type="submit" color="primary" :disabled="$v.$invalid">Login</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import {
        required,
        alphaNum,
        minLength
    } from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                username: '',
                password: ''
            }
        },

        computed: {
            usernameErrors () {
                const errors = []
                if (!this.$v.username.$dirty) return errors

                !this.$v.username.required && errors.push('Username is required.')
                !this.$v.username.alphaNum && errors.push('Username must be alphabetical and/or numerical.')
                !this.$v.username.minLength && errors.push('Username must be 4 characters minimum.')

                return errors
            },

            passwordErrors() {
                const errors = []
                if (!this.$v.password.$dirty) return errors

                !this.$v.password.required && errors.push('Password is required.')
                !this.$v.password.minLength && errors.push('Password must be 8 characters minimum.')

                return errors
            }
        },

        validations: {
            username: {
                required,
                alphaNum,
                minLength: minLength(4)
            },

            password: {
                required,
                minLength: minLength(6)
            }
        },

        methods: {
            onSubmit() {
                this.$v.$touch()

                if (this.$v.$error) return

                const formData = {
                    username: this.username,
                    password: this.password
                }

                this.$store.dispatch('login', formData)
            }
        }
    }
</script>
