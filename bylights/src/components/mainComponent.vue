<template>
  <div class="mainClass">
    <h1>{{ msg }}</h1>
  </div>
</template>

<script>
  export default {
    name: 'mainComponent',
    props: {
      msg: String
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  div{
    background-color: rgb(153, 243, 218);
  }
</style>
