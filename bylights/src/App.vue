<template>
  <div>
    <h1>{{ message }}</h1>
    <input type="text" ref="inputText">
    <button @click="handleClickButton">button</button>
  </div>
  <div v-if="showModal">
    <!--@close è emit del componente. genitore invia dati, figlio risponde ad eventi attraverso emit, senza modificare-->
    <ModalComponent :header="header" :theme="theme" :showModal="showModal" @close="toggleModal"/>
  </div>
  <button @click="toggleModal">open modal</button>
</template>

<script>
  import ModalComponent from './components/ModalComponent.vue'

  export default {
    name: 'App',
    components: {ModalComponent},
    data() {
      return {
        message: 'My first vue web-app',
        header: 'sign up with account',
        theme: 'sale',
        showModal: false
      };
    },
    methods: {
      changeMessage() {
        this.message = 'bye bye'
      },
      handleClickButton(){
        this.$refs.inputText.classList.add('active') //aggiunge classe active a tale ref specifica
      },
      toggleModal(){
        this.showModal = !this.showModal
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
