<template>
    <v-app>
        <v-content>
            <v-container class="fill-height fluid">
                <v-row align="center" justify="center">
                    <v-col cols="12" md="4">
                        <v-skeleton-loader class="mb-6" :type="loadingCard" />
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
export default {
    computed: {
        loadingCard() {
            return this.$store.getters.loadingState.type
        }
    }
}
</script>
