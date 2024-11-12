<template>
  <div v-if="badge">
    <h1>BADGES DETAILS</h1>
    <h2>{{ badge.title }}</h2>
    <p>The badge ID is: {{ id }}</p>
    <p>Dettagli del badge: {{ badge.details }}</p>
  </div>
  <div v-else>
    <p>Loading badge details...</p>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios'; // Assicurati di aver importato Axios

export default {
  name: 'BadgesDetailsView',
  setup() {
    const route = useRoute();
    const id = ref(route.params.id);
    const badge = ref(null);
    const error = ref(null);

    const fetchBadgeDetails = async () => {
      try {
        const response = await axios.get(`${process.env.VUE_APP_API_BASE_URL}/src/db/api/BadgeDetails.php`, {
          params: { id: id.value }
        });
        badge.value = response.data;
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

<style scoped>
/* Aggiungi i tuoi stili qui */
</style>