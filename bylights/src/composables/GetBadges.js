// src/composables/GetBadges.js

import { ref } from 'vue';
import axios from 'axios';

export default function GetBadges() {
  // Definizione delle proprietà reattive
  const badges = ref([]);
  const error = ref(null);
  const isLoading = ref(false);

  // Funzione per recuperare i badge dall'API
  const fetchBadges = async () => {
    isLoading.value = true; // Inizia il caricamento
    error.value = null;     // Resetta eventuali errori precedenti

    try {
      const response = await axios.get(`${process.env.VUE_APP_API_BASE_URL}/src/db/api/BadgesList.php`);
      badges.value = response.data; // Assegna i dati ricevuti
    } catch (err) {
      error.value = err.message || 'Errore nel recupero dei badge';
      console.error('Errore FetchBadges:', err);
    } finally {
      isLoading.value = false; // Termina il caricamento
    }
  };

  // Restituisce le proprietà e le funzioni per essere utilizzate nei componenti
  return {
    badges,
    error,
    isLoading,
    fetchBadges
  };
}
