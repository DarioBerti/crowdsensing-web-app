<template>
  <div>
    <h1>{{ message }}</h1>
    <input type="text" ref="inputText">
    <button @click="handleClickButton">button</button>
  </div>
  <!--div>
    <button @click="changeMessage">Cliccami</button>
  </div>
  <div v-if="true"></div> se evento non cambia spesso
  <div v-show="true"></div> se evento cambia spesso
  <div class="box" @mouseover="handleMouseOver">box</div> mouse events
  <div>
    <ul>  data un array, c'è metodo .filter()
      <li v-for="book in books" :class="{ fav: book.isFav}">  ciclare una lista books ed aggiungere una classe 'fav' ad ogni classe book che ha metodo isFav==true
      </li>
    </ul>
  </div-->
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      message: 'My first vue web-app'
    };
  },
  methods: {
    changeMessage() {
      this.message = 'bye bye'
    },
    handleClickButton(){
      this.$refs.inputText.classList.add('active') //aggiunge classe active a tale ref specifica
    }
  },
  computed: {
    //per liste che cambiano sempre in base a cosa viene cliccato(es lista dei preferiti)
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
