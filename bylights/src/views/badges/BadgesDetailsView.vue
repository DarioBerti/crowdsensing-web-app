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
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'BadgesDetailsView',
  setup() {
    const badge = ref(null);
    const route = useRoute();
    const id = ref(route.params.id);
    const error = ref(null);

    const fetchBadgeDetails = async () => {
      try {
        const response = await fetch('http://localhost:3000/badges/' + id.value);
        if(!response.ok){
            console.log("no data available");
        }
        badge.value = await response.json();
      } catch (err) {
        error.value = err.message;
        console.log(error.value);
      }
    };

    onMounted(fetchBadgeDetails);

    return {
      badge,
      id
    };
  }
};
</script>

<style></style>