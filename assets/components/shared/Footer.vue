<template>
  <v-footer app>
      <span>&copy; 2019</span>
  </v-footer>
</template>

<script>
  export default {
    data() {
      return {

      }
    }
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
