<template>
  <div id="footer" class="fixed-bottom">
    <div class="container">
      <div class="footer-block">
        <ul class="list-group">
          <li class="list-group-item">Contact us</li>
          <li class="list-group-item">Privacy Policy</li>
        </ul>
      </div>
      <div class="footer-block">
        <ul class="list-group">
          <li class="list-group-item">KijiReader on Github</li>
          <li class="list-group-item">Twitter</li>
        </ul>
      </div>
    </div>
  </div>
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
