<template>
    <div id="signin">
        <form @submit.prevent="onSubmit">
            <div class="form-group">
                <label for="username" :class="{'is-invalid': $v.username.$error}">Username:</label>
                <input type="text" id="username" class="form-control" :class="{'is-invalid': $v.username.$error, 'is-valid': !$v.username.$invalid}" v-model="username" @blur="$v.username.$touch()">
                <div class="invalid-feedback" v-if="$v.username.$error">
                    Please enter your username.
                </div>
            </div>
            <div class="form-group">
                <label for="password" :class="{'is-invalid': $v.password.$error}">Password:</label>
                <input type="password" id="password" class="form-control" :class="{'is-invalid': $v.password.$error, 'is-valid': !$v.password.$invalid}" v-model="password" @blur="$v.password.$touch()">
                <div class="invalid-feedback" v-if="$v.password.$error">
                    Please enter your password.
                </div>
            </div>

            <button type="submit" class="btn btn-primary" :disabled="$v.$invalid">Login</button>
        </form>
    </div>
</template>

<script>
import { required, alphaNum, minLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            username: '',
            password: ''
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
            const formData = {
                username: this.username,
                password: this.password
            }

            this.$store.dispatch('login', formData)
        }
    }
}
</script>

<style lang="scss" scoped>
// #signin {
//     width: 100%;
// }
</style>
