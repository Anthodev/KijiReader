<template>
    <v-form class="col-6 mt-6 mr-n8" @submit.prevent="onSubmit">
        <v-container>
            <v-row>
                <v-col>
                    <v-text-field class="mt-5" placeholder="Add a feed" v-model="inputFeed" @input="$v.inputFeed.$touch()"></v-text-field>
                    <v-divider class="mx-4" inset vertical></v-divider>
                </v-col>
                <v-col cols="2">
                    <v-btn class="my-4" color="primary" :disabled="$v.$invalid">+</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import { required, url, helpers } from 'vuelidate/lib/validators'

const isFeed = helpers.regex('url', /http[s?]?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)\b(.rss|.xml|.atom)$/)

export default {
    data () {
        return {
            inputFeed: ''
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
            const formData = {
                feedUrl: this.inputFeed
            }

            this.$store.dispatch('addFeed', formData)
        }
    }
}
</script>

<style lang="scss" scoped>
</style>
