<template>
    <!--contenitore parent che contiene elementi che si sovrappongono-->
    <div class = "mapLayout">
        <div ref="mapContainer" class="full-screen-map" ></div>

        <div class="record-icon">
            <div v-if="changeFlag">
                <!--binding per svg-->
                <img :src="recordIcon" alt="record icon" class = "record-style" @click="switchRecording(); startRecording();">
            </div>
            <div v-else>
                <!--binding per svg-->
                <img :src="stopRecordIcon" alt="stop record icon" class = "stop-record-style" @click="switchRecording(); stopRecording();">
            </div>
        </div>


        <div v-if="changeFlag">
            <div class="saved-paths">
                <!-- Modifica il gestore dell'evento click -->
                <a @click="toggleSavedPaths" class="SavedPathsRoutingLink">
                    saved paths
                </a>
            </div>
        </div>
        <div v-else>
            <div class="saved-paths">
                <p class="walkingText">walking...</p>
            </div>
        </div>
        
        <!-- Overlay semi-trasparente -->
        <div v-if="showSavedPaths" class="overlay" @click="toggleSavedPaths"></div>
         
        <SuccessPopup v-if="showSuccessPopup" @close="showSuccessPopup=false"/>

        <FailurePopup v-if="showFailurePopup" @close="showFailurePopup=false"/>

        <SavedPathsView v-if="showSavedPaths" />
        </div>
</template>

<style scoped>
    .full-screen-map {
        height: 100%;   /*utilizza intera altezza di contenitore parent */
    }

    .mapLayout{
        position: relative; /*posizione relativa per container parent */
        height: 100vh;
        top: 0;
    }
    .record-icon{
        position: absolute; /*interrompe flusso del documento e viene messo in sovrapposizione in base anche al z-index */
        bottom: 10px; /* Posiziona 10px sopra il fondo del contenitore */
        left: 50%; /* Posiziona a metà del contenitore sull'asse orizzontale */
        transform: translateX(-50%);
        z-index: 1000;  /*così elemento appare al di sopra degli altri elementi del contenitore */
        text-align: center;
    }
    .record-style{
        height: 10%;
        width: 10%;
    }
    .stop-record-icon{
        position: absolute; /*interrompe flusso del documento e viene messo in sovrapposizione in base anche al z-index */
        bottom: 10px;
        left: 50%; /* Posiziona a metà del contenitore sull'asse orizzontale */
        transform: translateX(-50%); /* Sposta a sinistra di metà della sua larghezza per centrarlo */
        z-index: 1000;  /*così elemento appare al di sopra degli altri elementi del contenitore */
        text-align: center;
    }
    .stop-record-style{
        height: 10%;
        width: 10%;
    }
    .saved-paths{
        position: absolute;
        z-index: 1500;

        top: 10px;
        left: 10px;
    }
    .SavedPathsRoutingLink {
        background-color: white; 
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        
        /*estetica link */
        color: black;
        display: inline-block;
        cursor: pointer;

        font-family: Avenir, Helvetica, Arial, sans-serif;
        font-weight: bold;
        text-decoration: none;
    }
    .SavedPathsRoutingLink:hover{
        background-color: #eee;
    }
    .walkingText {
        margin: 0;
        background-color: white;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        color: green;
        font-family: Avenir, Helvetica, Arial, sans-serif;
        font-weight: bold;

        animation: blink 1.2s ease-in-out infinite;
    }

    .saved-paths-popup {
        position: absolute;
        top: 10%;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2000; /*superiore all'index della mappa */
        background-color: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 10px;
        max-height: 80vh;
        overflow-y: auto;
        width: 80%; /*larghezza per schermi mobile */
        background-color: rgba(255, 255, 255, 0.7);

        /*nasconde la scroll bar */
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .saved-paths-popup::-webkit-scrollbar {
      display: none;
    }

    .mapLayout .full-screen-map {
        pointer-events: auto;
    }

    .mapLayout .full-screen-map.popup-open {
        pointer-events: none;
    }
    
    /* Overlay semi-trasparente */
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Sfondo semi-trasparente */
      z-index: 1500; /* superiore alla mappa ma inferiore al popup */
    }

    /* gestisce quando popup è aperta sulla mappa*/
    .full-screen-map.popup-open {
      pointer-events: none;
    }

    .success-message {
      color: green;
      font-size:30px;
      margin-left: 10px;
      z-index: 1500; /* Superiore alla mappa ma inferiore al popup */
    }

    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }

    @media (max-width: 600px) {
        .record-style{
            height: 25%;
            width: 25%
        }
    }
    @media (max-width: 600px) {
        .stop-record-style{
            height: 25%;
            width: 25%
        }
    }

    /* Schermi medi*/
    @media screen and (min-width: 768px) {
      .saved-paths-popup {
        width: 60%;
      }
    }

    /* Schermi grandi*/
    @media screen and (min-width: 1024px) {
      .saved-paths-popup {
        width: 40%;
      }
    }
</style>

<script>
    import 'leaflet/dist/leaflet.css'
    import 'leaflet/dist/leaflet.css'
    import {onMounted, ref} from 'vue'
    import L from 'leaflet'
    import recordIcon from '@/assets/record-svg.svg'
    import stopRecordIcon from '@/assets/stop-record-svg.svg'
    import axios from 'axios'
    import { useRouter } from 'vue-router'
    import SavedPathsView from '@/views/SavedPathsView.vue';
    import SuccessPopup  from '@/views/SuccessPopup.vue'
    import FailurePopup from '@/views/FailurePopup.vue'

    export default{
        name: 'MapView',
        components: {
            SavedPathsView,
            SuccessPopup,
            FailurePopup
        },

        setup(){
            const lat = ref(0);
            const lng = ref(0);
            const map = ref();
            const mapContainer = ref();
            const router = useRouter();
            const user = ref({});
            const changeFlag = ref(true);
            const brightnessValues = ref([]);
            //let animationFrameId = null;
            let stream = null;
            let video = null;
            let totalAverageBrightness = ref(0);
            const recordedLat = ref(0);
            const recordedLng = ref(0);
            const pathDate = ref(new Date().toISOString());
            const startTime = ref(null); // Memorizza il timestamp di inizio
            const recordingDuration = ref(0); // Durata della registrazione in secondi
            const markerRef = ref(null);
            const showSavedPaths = ref(false);
            //let timeoutId = null;
            const isRecording = ref(false);
            const showSuccessPopup = ref(false);
            const showFailurePopup = ref(false);
            let listPoints = ref([]);
            let intervalId = null;

            const toggleSavedPaths = () => {
                showSavedPaths.value = !showSavedPaths.value;
            };

            const created = async() => {
                axios.get(`http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/user.php`, {
                    withCredentials: true})
                .then(response => {
                    if (response.data.loggedIn) {
                        user.value = response.data.user;
                        console.log("id utente loggato: ", user.value.id);
                        console.log("username utente loggato: ", user.value.username);
                    } else {
                        // Non autenticato, reindirizza al login
                        console.log("utente non autenticato");
                        router.push('/');
                    }
                })
                .catch(() => {
                    // Errore, reindirizza al login
                    console.log("errore catch");
                    router.push('/');
                });

            }

            const blueIcon = new L.Icon({
              iconUrl: require('leaflet/dist/images/marker-icon.png'),
              shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              shadowSize: [41, 41]
            });

            const redIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                shadowSize: [41, 41]
            });


            // L.Marker.prototype.options.icon = defaultIcon;
            
            const getLocation = async() => {
                return new Promise((resolve, reject) => {
                    if(navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(position => {
                            lat.value = position.coords.latitude
                            lng.value = position.coords.longitude

                            resolve()
                        }, reject)
                    }else{
                        reject('Geolocation is not supported in this browser')
                    }
                })
            }

            const openMap = async() => {
                try{
                    //legge la posizione iniziale e set di latitudine, lungitudine e marker iniziale
                    await getLocation();

                    //set del map.value
                    map.value = L.map(mapContainer.value, {zoomControl: false}).setView([lat.value, lng.value], 15);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map.value);

                    //aggiunta marker alla mappa
                    markerRef.value = L.marker([lat.value, lng.value], { icon: blueIcon }).addTo(map.value);

                    // Utilizza setTimeout per richiamare invalidateSize dopo che la mappa è stata caricata
                    setTimeout(function () {
                        map.value.invalidateSize();
                    }, 100);
                }catch (error){
                    console.log('Failed to get Location:', error)
                }

            }

             const switchRecording = () => {
                 changeFlag.value = !changeFlag.value;
             }

            const startRecording = () => {
                isRecording.value = true;

                //azzera array di oggetti per ogni punto
                listPoints.value = [];

                //setta il marker nella nuova posizione in cui si è appena incominciato a registrare
                getLocation();
                recordedLat.value = lat.value;
                recordedLng.value = lng.value;

                //cambia colore del marker da blu a rosso
                markerRef.value.setIcon(redIcon);

                //inizia a calcolare il tempo
                startTime.value = Date.now();

                //questa funzione è asincrona e restituisce una promise, quindi c'è il rischio di interrompere la registrazione prima che l'operazione asincrona sia finita
                //  portando ad una finta fine registrazione
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(s => {
                    if (!isRecording.value) {
                      // Se la registrazione è stata interrotta prima che getUserMedia sia completato
                      s.getTracks().forEach((track) => track.stop());
                      return;
                    }

                    stream = s;
                    video = document.createElement('video');
                    video.srcObject = stream;
                    video.autoplay = true;
                    video.playsInline = true;

                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });

                    video.addEventListener('loadedmetadata', () => {
                        if (!isRecording.value) {
                          // Se la registrazione è stata interrotta prima che i metadati siano caricati
                          return;
                        }
                        
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;

                        // Delay iniziale per la calibrazione della telecamera
                           setTimeout(() => {
                               if (!isRecording.value) return;

                               const update = () => {
                                   if (!isRecording.value || !video || video.paused || video.ended) return;
                                   ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                   try {
                                       const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                                       let total = 0;
                                       for (let i = 0; i < imageData.data.length; i += 4) {
                                           const r = imageData.data[i];
                                           const g = imageData.data[i + 1];
                                           const b = imageData.data[i + 2];
                                           total += 0.299 * r + 0.587 * g + 0.114 * b;
                                       }
                                       const avgBrightness = total / (imageData.data.length / 4);
                                       brightnessValues.value.push(avgBrightness);
                                       console.log('Luminosità media per punto:', avgBrightness);

                                       //calcolo di lat e lng per ogni punto in cui viene calcolata la luminosità
                                       getLocation()
                                       const lat_current = lat.value;
                                       const lng_current = lng.value;
                                    
                                       //aggiunta di lat,long e brightness per ogni punto in array di oggetti
                                       listPoints.value.push({ lat: lat_current, lng: lng_current, brightness: avgBrightness });

                                       console.log("vet obj: ", listPoints.value)

                                   } catch (error) {
                                       console.error('Errore getImageData:', error);
                                   }
                               };

                               // Esegue la funzione update ogni 5000 ms
                               intervalId = setInterval(() => {
                                   if (isRecording.value) update();
                               }, 5000);
                           }, 4000);

                    });

                    video.addEventListener('error', (err) => {
                        console.error('Errore video:', err);
                    });
                    })
                    .catch(err => console.error('Errore accesso webcam:', err));
            };

            const stopRecording = () => {
                isRecording.value = false; // Indica che la registrazione è stata interrotta

                //ritorna a marker blu iniziale
                //cambia colore del marker da blu a rosso
                markerRef.value.setIcon(blueIcon);

                // Ferma l'intervallo di aggiornamento
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
                
                // Ferma tutti i track del media stream
                if (stream) {
                    stream.getTracks().forEach((track) => track.stop());
                    stream = null;
                }
                
                // Rimuovi l'elemento video dal DOM
                if (video) {
                    video.pause();
                    video.srcObject = null;
                    video.remove();
                    video = null;
                }

                //controlla che abbastanza dati siano stati raccolti prima di mandare i dati al profilo utente
                if(checkEnoughValues()){
                    
                    //calcola la media totale di tutti i valori a fine della registrazione
                    totalAverageBrightness.value = calculateAverage();
                    console.log("total average a fine registrazione: ", totalAverageBrightness.value);
    
                    //calcola langitudine e latitudine
                    console.log("lat e long REGISTRATI", recordedLat.value, recordedLng.value);

                    //calcola la date
                    pathDate.value = new Date().toISOString().slice(0, 10);
                    console.log("pathdate: ", pathDate.value);
    
                    //calcola il tempo
                    if (startTime.value) {
                        const endTime = Date.now();
                        const temp = ((endTime - startTime.value) / 1000)/60; // Durata in minuti
                        recordingDuration.value = parseFloat(temp.toFixed(2));
                        console.log("Durata registrazione (minuti):", recordingDuration.value);
                    }

                    //manda i dati del path per essere salvato nel profilo utente
                    //totalAverageBrightness, recordedlat, recordedlng, pathDate, recordingDuration
                    insertPath();

                }

                // Resetta i valori di brightnessValues
                brightnessValues.value = [];
                console.log('Registrazione interrotta.');
            };

            const calculateAverage = () => {
                //controlla se sono stati registrati abbastanza dati
                if(!checkEnoughValues()){
                    return 0;
                }

                const totalSum = brightnessValues.value.reduce((acc, value) => acc + value, 0);
                const avg = totalSum / brightnessValues.value.length;
                
                return avg;
            }

            const checkEnoughValues = () => {
                if(brightnessValues.value.length <= 7){
                    console.log("not enough values have been registered, walk for a little bit longer :)");
                    showFailurePopup.value = true;
                    return 0;
                }
                return 1;
            }

            const insertPath = async() => {
                //variabili all'interno di pathData sono separate dal resto del codice
                const pathData = {
                    averageBrightness: totalAverageBrightness.value,
                    initialLatitude: recordedLat.value,
                    initialLongitude: recordedLng.value,
                    pathDate: pathDate.value,
                    pathTime: recordingDuration.value,
                    userId: user.value.id,
                    recordedPoints: listPoints.value
                };

                try {
                    //richiesta a file endpoint php
                    const response = await axios.post(`http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/insertPath.php`, pathData, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        withCredentials: true
                    });
                    
                    if (response.data.success) {
                        //inserimento path fatto con successo
                        console.log("inserimento path avvenuto con successo");
                        showSuccessPopup.value = true;
                    } else {
                        // inserimento path fallito
                        console.log("errore nell'inserimento response.data:", response.data);
                    }
                } catch (error) {
                    console.error("Errore durante la richiesta al backend: ", error);
                }
            }

            onMounted(() => {
                created();
                openMap();
            })

            return{lat, lng, getLocation, map, mapContainer, openMap, recordIcon, changeFlag, switchRecording, stopRecordIcon, created, user, startRecording, stopRecording, totalAverageBrightness, checkEnoughValues, recordedLat, recordedLng, pathDate, calculateAverage, startTime, recordingDuration, insertPath, showSavedPaths,
                toggleSavedPaths, SavedPathsView, showSuccessPopup, showFailurePopup}
        }
    }
</script>