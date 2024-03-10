<template>
  <div>
    <h1>{{ message }}</h1>
    <button @click="changeMessage">Cliccami</button>
  </div>
  <!--div v-if="true"></div> se evento non cambia spesso
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
      message: 'Ciao Mondo!'
    };
  },
  methods: {
    changeMessage() {
      this.message = 'bye bye'
    },
    handleMouseOver(e){
      console.log(e, e.type);
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
