<template>
  <SignupForm />
</template>

<script>
  import SignupForm from './components/SignupForm.vue'

  export default {
    name: 'App',
    components: { SignupForm }
  }
</script>

<style>
  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
  }

  body{
    margin: 0%;
    background: #eee;
  }
</style>
