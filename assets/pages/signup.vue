<template>
  <v-card class="elevation-12">
    <v-toolbar color="indigo" dark flat>
      <v-toolbar-title>KijiReader - Create an account</v-toolbar-title>
      <v-spacer />
    </v-toolbar>
    <v-card-text>
      <v-form id="signupForm" @keyup.native.enter="onSubmit" @submit.prevent="onSubmit">
        <v-text-field id="username" label="Login" name="username" prepend-icon="mdi-account" type="text" v-model="username" @blur="$v.username.$touch()" :error-messages="usernameErrors" placeholder="Enter your username" required autofocus></v-text-field>

        <v-text-field id="email" label="Email" name="email" prepend-icon="mdi-at" type="text" v-model="email" @blur="$v.email.$touch()" :error-messages="emailErrors" placeholder="Enter your email" required></v-text-field>

        <v-text-field id="password" label="Password" name="password" prepend-icon="mdi-lock" type="password" v-model="password" @blur="$v.password.$touch()" :error-messages="passwordErrors" placeholder="Enter a password" required></v-text-field>
        <v-progress-linear :value="passwordLengthProgress" :color="colorPassword" height="7"></v-progress-linear>

        <v-text-field id="passwordConfirm" label="Confirm the password" name="passwordConfirm" prepend-icon="mdi-lock" type="password" v-model="passwordConfirm" @blur="$v.passwordConfirm.$touch()" :error-messages="passwordConfirmErrors" placeholder="Confirm the password" required></v-text-field>
        <v-progress-linear :value="passwordConfirmLengthProgress" :color="colorPasswordConfirm" height="7" />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-card-text class="caption"><nuxt-link to="/signin">Login to your account</nuxt-link></v-card-text>
      <v-spacer></v-spacer>
      <v-btn form="signupForm" type="submit" color="primary" :loading="loading" :disabled="$v.$invalid">Create an account</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { required, alphaNum, email, minLength, sameAs } from 'vuelidate/lib/validators'

export default {
  layout: 'auth',

  data() {
    return {
      username: '',
      password: '',
      passwordConfirm: '',
      email: '',
      loading: false
    }
  },

  computed: {
    usernameErrors () {
      const errors = []
      if (!this.$v.username.$dirty) return errors

      !this.$v.username.required && errors.push('An username is required.')
      !this.$v.username.alphaNum && errors.push('The username must be alphabetical and/or numerical.')
      !this.$v.username.minLength && errors.push('The username must be 4 characters minimum.')

      return errors
    },

    emailErrors() {
        const errors = []
      if (!this.$v.email.$dirty) return errors

      !this.$v.email.required && errors.push('An email address is required.')
      !this.$v.email.email && errors.push('Your email is not on a valid format')
    },

    passwordErrors() {
        const errors = []
      if (!this.$v.password.$dirty) return errors

      !this.$v.password.required && errors.push('Password is required.')
      !this.$v.password.minLength && errors.push('Password must be 8 characters minimum.')

      return errors
    },

    passwordConfirmErrors() {
      const errors = []
      if (!this.$v.passwordConfirm.$dirty) return errors

      !this.$v.passwordConfirm.required && errors.push('Confirm the password.')
      !this.$v.passwordConfirm.sameAsPassword && errors.push('This field must be the same as the password')

        return errors
    },

    passwordLengthProgress() {
      return Math.min(100, this.password.length * 12.5)
    },

    passwordConfirmLengthProgress() {
      return Math.min(100, this.passwordConfirm.length * 12.5)
    },

    colorPassword() {
      return ['error', 'warning', 'success'][Math.floor(this.passwordLengthProgress / 40)]
    },

    colorPasswordConfirm() {
      return ['error', 'warning', 'success'][Math.floor(this.passwordConfirmLengthProgress / 40)]
    },
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
      this.$v.$touch()
      this.loading = !this.loading

      if (this.$v.$error) {
        this.loading = !this.loading
        return
      }

      if (this.$v.$error) return
      
      const formData = {
        username: this.username,
        email: this.email,
        password: this.password,
        role: 'User'
      }

      this.$store.dispatch('SIGNUP', formData).then(() => {
        this.$store.dispatch('SET_LOADING_STATE', {
          loadingState: {
            loading: true,
            type: "card-heading, list-item@6, text"
          }
        })
        
        this.loading = !this.loading
      })
    }
  }
}
</script>
