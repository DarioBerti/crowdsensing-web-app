<template>
    <div class="Home">
        <h1>Home title</h1>
        <!--piazzo il componente figlio PostList e gli passo come params 'posts'-->
        <PostList :posts="posts"/>
    </div>
</template>

<script>
    import PostList from '../components/PostListComponent.vue'
    import { ref } from 'vue'

    export default{
        name: 'HomeView',
        components: {PostList},
        setup(){
            const posts = ref([
                {title: 'title of post 1', body: 'body of post 1', id: 1},
                {title: 'title of post 2', body: 'body of post 2', id: 2},
            ])

            return{posts}
        }
    }
</script>

<style>
</style>