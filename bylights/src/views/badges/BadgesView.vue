<template>
    <h1>BADGES</h1>
    <div v-for="badge in badges" :key="badge.id">
        <h2>{{ badge.title }}</h2>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                badges: [
                    {"title":"titolo di badge 1!!!", "id": 1, "details": "questo badge è mooolto importante"},
                    {"title":"titolo di badge 2!!!", "id": 2, "details": "questo badge è mooolto importante"},
                    {"title":"titolo di badge 3!!!", "id": 3, "details": "questo badge è mooolto importante"} 
                ]
            }
        }
    }
</script>
<style></style>