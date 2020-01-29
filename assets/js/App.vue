<template>
  <div class="container">
    <app-header></app-header>
    <app-footer></app-footer>
  </div>
</template>

<script>
  import Header from './components/shared/Header.vue';
  import Footer from './components/shared/Footer.vue';

  export default {
    name: 'App',

    data() {
      return {
        
      }
    },

    components: {
      appHeader: Header,
      appFooter: Footer,
    }
  }
</script>
