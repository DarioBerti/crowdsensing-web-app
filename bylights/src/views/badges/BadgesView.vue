<template>
    <h1>BADGES</h1>
    <div v-for="badge in badges" :key="badge.id" class="badge">
        <!--route-param dinamico 'id'. in questo caso cambia in base al badge.id-->
        <router-link :to="{ name: 'BadgesDetails', params: { id: badge.id } }" >
            <h2>{{ badge.title }}</h2>
        </router-link>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                badges: [
                    {title:"titolo di badge 1!!!", id: 1, details: "questo badge è mooolto importante"},
                    {title:"titolo di badge 2!!!", id: 2, details: "questo badge è mooolto importante"},
                    {title:"titolo di badge 3!!!", id: 3, details: "questo badge è mooolto importante"} 
                ]
            }
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