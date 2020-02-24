<template>
  <div class="container-fluid">
    <app-header></app-header>
    <app-main></app-main>
    <app-footer></app-footer>
  </div>
</template>

<script>
  import Header from './components/shared/Header.vue';
  import Footer from './components/shared/Footer.vue';
  import Main from './components/Main.vue';

  export default {
    name: 'App',

    data() {
      return {
        
      }
    },

    components: {
      appHeader: Header,
      appFooter: Footer,
      appMain: Main,
    }
  }
</script>
