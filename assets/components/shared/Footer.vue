<template>
  <v-row>
    <v-footer app>
      <v-row>
        <span>&copy; {{ new Date().getFullYear() }}</span>
      </v-row>
    </v-footer>
  </v-row>
</template>

<script>
// import Alert from './Alert'

export default {
  data() {
    return {

    }
  },
}
</script>

<style scoped>
  #footer {
    background-color: #202120;
    height: 8rem;
    padding: 1rem;
  }

  #footer .container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
    width: 50%;
  }

  .footer-block {
    flex-grow: 1;
    text-transform: uppercase;
  }
</style>
