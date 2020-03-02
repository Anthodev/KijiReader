<template>
    <div id="signup">
        <form @submit.prevent="onSubmit">
            <div class="form-group">
                <label for="username" :class="{'is-invalid': $v.username.$error}">Username:</label>
                <input type="text" id="username" class="form-control" :class="{'is-invalid': $v.username.$error, 'is-valid': !$v.username.$invalid}" v-model="username" @blur="$v.username.$touch()">
                <div class="invalid-feedback" v-if="$v.username.$error">
                    Please provide an username.
                </div>
            </div>
            <div class="form-group">
                <label for="password" :class="{'is-invalid': $v.password.$error}">Password:</label>
                <input type="password" id="password" class="form-control" :class="{'is-invalid': $v.password.$error, 'is-valid': !$v.password.$invalid}" v-model="password" @blur="$v.password.$touch()">
                <small id="passwordHelp" class="form-text text-muted">Alphanumerical and 6 characters minimum</small>
                <div class="invalid-feedback" v-if="$v.password.$error">
                    Please provide a valid password.
                </div>
            </div>
            <div class="form-group">
                <label for="passwordConfirm" :class="{'is-invalid': $v.passwordConfirm.$error}">Confirm password:</label>
                <input type="password" id="passwordConfirm" class="form-control" :class="{'is-invalid': $v.passwordConfirm.$error, 'is-valid': !$v.passwordConfirm.$invalid}" v-model="passwordConfirm" @blur="$v.passwordConfirm.$touch()">
                <div class="invalid-feedback" v-if="$v.passwordConfirm.$error">
                    Both passwords must be the same.
                </div>
            </div>
            <div class="form-group">
                <label for="email" :class="{'is-invalid': $v.email.$error}">Email:</label>
                <input type="email" id="email" class="form-control" :class="{'is-invalid': $v.email.$error, 'is-valid': !$v.email.$invalid}" v-model="email" @blur="$v.email.$touch()">
                <div class="invalid-feedback" v-if="$v.email.$error">
                    Please provide a valid email.
                </div>
            </div>

            <button type="submit" class="btn btn-primary" :disabled="$v.$invalid">Create account</button>
        </form>
    </div>
</template>

<script>
import { required, alphaNum, email, minLength, sameAs } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            username: '',
            password: '',
            passwordConfirm: '',
            email: ''
        }
    },

    validations: {
        username: {
            required,
            alphaNum,
            minLength: minLength(4)
        },
        
        email: {
            required,
            email
        },

        password: {
            required,
            minLength: minLength(6)
        },

        passwordConfirm: {
            required,
            sameAsPassword: sameAs('password')
        }
    },

    methods: {
        onSubmit() {
            const formData = {
                username: this.username,
                email: this.email,
                password: this.password,
                role: 'User'
            }

            this.$store.dispatch('signup', formData)
        }
    }
}
</script>

<style lang="scss" scoped>
#signup {
    width: 100%;
}
</style>
