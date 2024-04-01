<template>
    <div class="post-list">
        <!--uso props 'posts'-->
        <div v-for="post in posts" :key="post.id">
            <h2>id del post: {{ post.id }}</h2>
            <h3>{{ post.title }} {{ post.body }}</h3>
        </div>
    </div>
</template>

<script>
import { onMounted, onUnmounted, onUpdated } from 'vue'

    export default{
        //così posso usare DIRETTAMENTE il dato 'posts' che è stato passato come props, senza aggiungere nessun'altra
        //  variabile dato che 'posts' è già stato definito nel componente padre
        props: ['posts'],
        //se voglio usarlo dentro il setup lo passo così
        setup(){
            //esempi di lifecycle hooks
            onMounted(() => console.log("component mounted"))
            onUpdated(() => console.log("component updated"))
            onUnmounted(() => console.log("component un-mounted"))
        }
    }
</script>

<style></style>