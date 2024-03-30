<!--THIS PAGE IS A DETAILS PAGE, AN EXTENSION OF ANOTHER PAGE -> IT USES ROUTE PARAMS-->
<template>
    <div v-if="badge">
        <h1>Badges details page</h1>
        <h1>{{ badge.title }}</h1>
        <p>the badge id is: {{ id }}</p>
        <p>dettagli del badge: {{ badge.details }}</p>
        <p>the badge route param id is: {{ id }}</p>
    </div>
    <div v-else>
        <p>Loading badge details...</p>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                badge: null,
                id: this.$route.params.id
            }
        },
        mounted(){
            fetch('http://localhost:3000/badges/' + this.id)
                .then(res => res.json())
                .then(data => this.badge = data)
                .catch(err => console.log(err.message))
        }
    }
</script>
<style></style>