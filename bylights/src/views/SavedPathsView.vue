<template>
  <div class="saved-paths-popup">
    <div v-if="error">
      <p>Error: {{ error }}</p>
    </div>

    <div v-else-if="error === false">
      <div class="path-list">
        <div v-for="pathId in ListPathsId" :key="pathId" class="path-item">

          <div class="name-path" style="text-align: left;">
            <p>Path {{ pathId }}</p>
          </div>

          <div class="lamp-icon" style="text-align: right;">
            <!--binding per svg-->
            <img :src="streetLightIcon" alt="street light icon" class = "street-light">
          </div>

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
    import streetLightIcon from '@/assets/street-light-icon.svg'

  export default {
    name: 'SavedPathsView',

    setup() {
      const error = ref(null);
      const router = useRouter();
      const ListPathsId = ref([]);


      //prende dal database i path registrati da tale utente loggato in sessione.
      //VIEW PATH
      const getPathData = async() => {
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
        getPathData();
      })

      return {
        error,
        getPathData,
        ListPathsId,
        streetLightIcon
      };
    }
  };
</script>

<style>
  .path-list {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
</style>

<style scoped>
  .path-item {
    width: 80%;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    background-color: #E9F7FF;
    display: flex;
    align-items: center; /* Vertically center items */
    justify-content: space-between; /* Push text left, icon right */
  }

  .name-path{
    flex-grow: 1;
  }

  .path-item p {
    margin: 0;
    font-size: 20px;
    font-weight: bold;
    color: #555;
  }

  .street-light{
        height: 30px;
        width: 30px;
    }
</style>
