<!--THIS PAGE IS A DETAILS PAGE, AN EXTENSION OF ANOTHER PAGE -> IT USES ROUTE PARAMS-->
<template>
    <div>
        <h1>Badges details page</h1>
        <p>the badge route param id is: {{ id }}</p>
    </div>
</template>
<script>
    export default{
        data(){
            return{
                id: this.$route.params.id
            }
        }
    }
</script>
<style></style>