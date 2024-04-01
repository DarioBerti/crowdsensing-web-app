<template>
    <div class="post-list">
        <div v-for="post in posts" :key="post.id">
            <h2>id del post: {{ post.id }}</h2>
            <h3>{{ post.title }} {{ post.body }}</h3>
        </div>
    </div>
</template>

<script>
    export default{
        //così posso usare DIRETTAMENTE il dato 'posts' che è stato passato come props, senza aggiungere nessun'altra variabile
        props: ['posts'],
        //se voglio usarlo dentro il setup lo passo così
        setup(props){
            console.log(props.posts);
        }
    }
</script>

<style></style>