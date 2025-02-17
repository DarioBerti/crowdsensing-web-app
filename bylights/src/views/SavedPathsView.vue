<template>
  <div class="saved-paths-popup">
    <div v-if="error">
      <p>Error: {{ error }}</p>
    </div>

    <div v-else-if="error === false">
      <div class="path-list">

        <div v-for="(path) in ListPaths" :key="path.path_id" class="path-item" @click="goToPathDetailsPage(path.path_id)">

          <div class="name-path" style="text-align: left;">
            <p>{{ path.path_date }}</p>
          </div>

          <div class="icon-and-percentage">
            <div class="lamp-icon">
              <img :src="streetLightIcon" alt="street light icon" class="street-light">
            </div>
            <p :style="{ color: getColor(calculateBrightness(path.brightness)) }">
              {{ calculateBrightness(path.brightness) }} %
            </p>
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
      const ListPaths = ref([]);

      //prende dal database i path registrati da tale utente loggato in sessione.
      const getPathsId = async() => {
        axios.get(`http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/SavedPaths.php`, {
            withCredentials: true})
        .then(response => {
          console.log("Response data:", response.data);

          if (response.data.success) {
              // Salva la lista dei path
              error.value = false;
              ListPaths.value = response.data.paths;
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

      const calculateBrightness = (brightness) => {
        let maximumBrightness = 270;
        return Math.floor((100 * brightness) / maximumBrightness);
      }

      // determina il colore in base alla percentuale
      const getColor = (percentage) => {
        if (percentage >= 0 && percentage < 35) {
          return 'red';
        } else if (percentage >= 35 && percentage < 45) {
          return 'orange';
        } else if (percentage >= 45 && percentage <= 100) {
          return 'green';
        } else {
          return 'black'; // Colore di default
        }
      };

      const goToPathDetailsPage = (path_id) => {
        router.push({ path: `/pathDetails/${path_id}` });
      }

      onMounted(() => {
        getPathsId();
      })

      return {
        error,
        getPathsId,
        ListPaths,
        streetLightIcon,
        calculateBrightness,
        getColor,
        goToPathDetailsPage
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
  .name-path {
    flex-grow: 1;
    font-style: italic; 
    color: #666;         /* un grigio medio che non sovrasta il resto */
    font-size: 0.9rem;   /* leggermente più piccola */
    letter-spacing: 0.5px; /* spaziatura delicata fra le lettere */
    opacity: 0.9;        /* un velo di trasparenza per un effetto più soft */
  }


  .path-item {
    width: 80%;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    background-color: #E9F7FF;
    display: flex;
    align-items: center;
    justify-content: space-between;
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

    /*icona e percentuale */
    .icon-and-percentage {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }

    .lamp-icon {
      margin-right: 4px;
      margin-bottom: 4px;
    }
</style>
