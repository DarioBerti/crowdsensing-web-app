<template>
    <h1>BADGES</h1>
    <div v-for="badge in badges" :key="badge.id" class="badge">
        <!--passa nel router-link il route-param dinamico 'id'. in questo caso collegato al badge.id-->
        <router-link :to="{ name: 'BadgesDetails', params: { id: badge.id } }" >
            <h2>{{ badge.title }}</h2>
        </router-link>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                badges: []
            }
        },
        mounted(){
            fetch('http://localhost:3000/badges')
            .then(res => res.json())
            .then(data => this.badges = data)
            .catch(err => console.log(err.message))
        }
    }
</script>
<style>
    .badge h2{
        background: #f4f4f4;
        padding: 20px;
        border-radius: 10px;
        margin: 10px auto;
        max-width: 600px;
        cursor: pointer;
        color: #444;
    }
    .badge h2:hover{
        background: #ddd;
    }
    .badge a{
        text-decoration: none;
    }
</style>