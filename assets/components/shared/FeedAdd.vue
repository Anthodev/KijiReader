<template>
    <v-form class="col-6 mt-6 mr-n8" @submit.prevent="onSubmit">
        <v-container>
            <v-row>
                <v-col>
                    <v-text-field :loading="loading" :rules="serverError" :error-messages="inputFeedErrors" class="mt-5" placeholder="Add a feed" v-model="inputFeed" @input="$v.inputFeed.$touch()"></v-text-field>
                    <v-divider class="mx-4" inset vertical></v-divider>
                </v-col>
                <v-col cols="2">
                    <v-btn type="submit" class="my-4" color="primary" :loading="loading" :disabled="$v.$invalid">+</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import { required, url, helpers } from 'vuelidate/lib/validators'

// const isFeed = helpers.regex('url', /http[s?]?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)\b(.rss|.xml|.atom)$/)
const isFeed = helpers.regex('url', /http[s?]?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)/)

export default {
    data () {
        return {
            inputFeed: '',
            errorState: false,
            loading: false,
        }
    },

    computed: {
        inputFeedErrors() {
            const errors = []
            if (!this.$v.inputFeed.$dirty) return errors

            !this.$v.inputFeed.required && errors.push('Enter a feed url.')
            !this.$v.inputFeed.isFeed && errors.push('The url isn\'t in a valid format.')

            return errors
        },

        serverError() {
            return [ this.errorState != true || 'Unable to add this feed' ]
        }
    },

    validations: {
        inputFeed: {
            required,
            isFeed
        }
    },

    methods: {
        onSubmit() {
            this.$v.$touch()
            this.errorState = false
            this.loading = !this.loading

            if (this.$v.$error) {
                this.loading = !this.loading
                return
            }

            const formData = {
                feedUrl: this.inputFeed
            }

            this.$store.dispatch('addFeed', formData)
                .then(request => {
                    this.loading = !this.loading

                    if (this.$store.getters.serverError != '') {
                        this.errorState = true
                        this.$store.dispatch('clearServerError')
                    } else {
                        this.$v.$reset()
                        this.inputFeed = ''
                    }
                })
        }
    }
}
</script>
