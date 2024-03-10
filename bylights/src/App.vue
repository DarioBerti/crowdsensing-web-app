<template>
  <div>
    <h1>{{ message }}</h1>
    <button @click="saluta">Cliccami</button>
  </div>
  <div v-if="true"></div> <!--se evento non cambia spesso-->
  <div v-show="true"></div> <!--se evento cambia spesso-->
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      message: 'Ciao Mondo!'
    };
  },
  methods: {
    saluta() {
      this.message = 'bye bye'
    }
  }
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
