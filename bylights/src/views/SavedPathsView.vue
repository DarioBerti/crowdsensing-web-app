<template>
  <div>
    <div v-if="error">
      <p>Error: {{ error }}</p>
    </div>

    <div v-else-if="error === false">
      <h2>I tuoi percorsi salvati</h2>
      <div class="path-list">
        <div v-for="pathId in ListPathsId" :key="pathId" class="path-item">
          <p>Path ID: {{ pathId }}</p>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Caricamento dei dati...</p>
    </div>
  </div>
</template>


<script>
    import {onMounted, ref} from 'vue'
    import axios from 'axios'
    import { useRouter } from 'vue-router'

  export default {
    name: 'SavedPathsView',

    setup() {
      const error = ref(null);
      const router = useRouter();
      const ListPathsId = ref([]);


      //prende dal database i path registrati da tale utente loggato in sessione
      const getData = async() => {
        axios.get(`${process.env.VUE_APP_API_BASE_URL}/src/db/api/SavedPaths.php`, {
            withCredentials: true})
        .then(response => {
          console.log("Response data:", response.data);

          if (response.data.success) {
              // Salva la lista dei path
              error.value = false;
              ListPathsId.value = response.data.paths_id;
            } else {
              error.value = true;
              console.log("Error message from server:", response.data.message);
              router.push('/mapView');
          }
        })
        .catch(() => {
            // Errore, reindirizza al login
            error.value = true;
            console.log("errore catch");
            router.push('/mapView');
        });
      }

      onMounted(() => {
        getData();
      })

      return {
        error,
        getData,
        ListPathsId
      };
    }
  };
</script>

<style scoped>
.path-list {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.path-item {
  width: 80%;
  padding: 15px;
  margin: 10px 0;
  border: 1px solid #007BFF;
  border-radius: 8px;
  background-color: #E9F7FF;
  text-align: center;
}

.path-item p {
  margin: 0;
  font-size: 18px;
  color: #8d2ff2;
}
</style>
