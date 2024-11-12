<script>
export default {
  name: 'SavedPathsView',
  data() {
    return {
      info: null,
      error: null
    };
  },
   methods: {
     fetchData() {
       fetch(`${process.env.VUE_APP_API_BASE_URL}/src/db/api/SavedPaths.php`)
       .then(response => {
         if (!response.ok) {
           throw new Error('Network response was not ok');
         }
         return response.json();
       })
       .then(data => {
         this.info = data;
       })
       .catch(error => {
         this.error = error.message;
       });
     }
   },
   mounted() {
     this.fetchData();
   }
};
</script>

<template>
  <div>
    <h1>Data from Backend</h1>
    <div v-if="error">
      <p>Error: {{ error }}</p>
    </div>
    <div v-else-if="info">
      <p>Status: {{ info.status }}</p>
      <p>Message: {{ info.data.message }}</p>
    </div>
    <div v-else>
      <p>Loading data...</p>
    </div>
  </div>
</template>
