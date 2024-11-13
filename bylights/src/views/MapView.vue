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
                    <!--routing link alla pagina di MapRecordingView-->
                    <router-link to="/savedPathsViews" class="SavedPathsRoutingLink">
                        saved paths
                    </router-link>
                </div>
            </div>
            <div v-else>
                <div class="saved-paths">
                    <p class="walkingText">walking...</p>
                </div>
            </div>
        </div>
</template>

<style scoped>
    .full-screen-map {
        /*width: 100vw; 
        height: 100vh; 
        position: fixed; 
        top: 0;
        left: 0;*/
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
        transform: translateX(-50%); /* Sposta a sinistra di metà della sua larghezza per centrarlo */
        z-index: 1000;  /*così elemento appare al di sopra degli altri elementi del contenitore */
        text-align: center;
    }
    .record-style{
        height: 10%;
        width: 10%;
    }
    .stop-record-icon{
        position: absolute; /*interrompe flusso del documento e viene messo in sovrapposizione in base anche al z-index */
        bottom: 10px; /* Posiziona 10px sopra il fondo del contenitore */
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
        z-index: 1000;

        top: 10px;
        left: 10px;
    }
    .SavedPathsRoutingLink {
        background-color: white;  /* Colore di sfondo */
        border-radius: 10px;     /* Angoli arrotondati */
        padding: 10px;           /* Padding intorno al testo */
        text-align: center;      /* Allineamento del testo */
        
        /* Altre proprietà per abbellire il link */
        color: black;            /* Colore del testo */
        display: inline-block;   /* Renderlo un blocco ma con comportamento in linea */
        cursor: pointer;         /* Cambiare il cursore per indicare che è cliccabile */

        font-family: Avenir, Helvetica, Arial, sans-serif;
        font-weight: bold;
        text-decoration: none;
    }
    .SavedPathsRoutingLink:hover{
        background-color: #eee;
    }
    .walkingText {
        margin: 0;
        background-color: white;  /* Colore di sfondo */
        border-radius: 10px;     /* Angoli arrotondati */
        padding: 10px;           /* Padding intorno al testo */
        text-align: center;      /* Allineamento del testo */
        
        /* Altre proprietà per abbellire il link */
        color: green;            /* Colore del testo */

        font-family: Avenir, Helvetica, Arial, sans-serif;
        font-weight: bold;

        animation: blink 1.2s ease-in-out infinite;
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
</style>

<script>
    import 'leaflet/dist/leaflet.css'
    // import { Icon } from 'leaflet'
    import 'leaflet/dist/leaflet.css'
    import {onMounted, ref} from 'vue'
    import L from 'leaflet'
    import recordIcon from '@/assets/record-svg.svg'
    import stopRecordIcon from '@/assets/stop-record-svg.svg'
    import axios from 'axios'
    import { useRouter } from 'vue-router'

    export default{
        name: 'MapView',

        setup(){
            const lat = ref(0);
            const lng = ref(0);
            const map = ref();
            const mapContainer = ref();
            const router = useRouter();
            const user = ref({});
            const changeFlag = ref(true);
            const brightnessValues = ref([]);
            let animationFrameId = null;
            let stream = null;
            let video = null;
            let totalAverageBrightness = ref(0);
            const recordedLat = ref(0);
            const recordedLng = ref(0);
            const pathDate = ref(new Date().toISOString());
            const startTime = ref(null); // Memorizza il timestamp di inizio
            const recordingDuration = ref(0); // Durata della registrazione in secondi
            const markerRef = ref(null);

            

            const created = async() => {
                axios.get(`${process.env.VUE_APP_API_BASE_URL}/src/db/api/user.php`, {
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
                    map.value = L.map(mapContainer.value, {zoomControl: false}).setView([lat.value, lng.value], 16);
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
                 changeFlag.value = !changeFlag.value
             }

            const startRecording = () => {
                //setta il marker nella nuova posizione in cui si è appena incominciato a registrare
                getLocation();
                recordedLat.value = lat.value;
                recordedLng.value = lng.value;

                //cambia colore del marker da blu a rosso
                markerRef.value.setIcon(redIcon);

                //inizia a calcolare il tempo
                startTime.value = Date.now();

                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(s => {
                    stream = s;
                    video = document.createElement('video');
                    video.srcObject = stream;
                    video.autoplay = true;
                    video.playsInline = true;

                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });

                    video.addEventListener('loadedmetadata', () => {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;

                        //set timeout in modo di dare tempo alla telecamera di autoregolarsi
                        setTimeout( () => {
                            //funzione principale che calcola i valori di luminosità
                            const update = () => {
                            if (!video || video.paused || video.ended) return;
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

                                console.log('Luminosità media:', avgBrightness);
                            } catch (error) {
                                console.error('Errore getImageData:', error);
                            }
                            animationFrameId = requestAnimationFrame(update);
                            };
                            update();
                        }, 4000) // time out di 3 secondi

                    });

                    video.addEventListener('error', (err) => {
                        console.error('Errore video:', err);
                    });
                    })
                    .catch(err => console.error('Errore accesso webcam:', err));
            };


            const stopRecording = () => {
                //ritorna a marker blu iniziale
                //cambia colore del marker da blu a rosso
                markerRef.value.setIcon(blueIcon);

                // Ferma l'animazione
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
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
                    //totalAverageBrightness, latitude, longitude, date, recordingDuration





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
                if(brightnessValues.value.length <= 50){
                    console.log("not enough values have been registered, walk for a little bit longer :)");
                    return 0;
                }
                return 1;
            }

            onMounted(() => {
                created();
                openMap();
            })

            return{lat, lng, getLocation, map, mapContainer, openMap, recordIcon, changeFlag, switchRecording, stopRecordIcon, created, user, startRecording, stopRecording, totalAverageBrightness, checkEnoughValues, recordedLat, recordedLng, pathDate, calculateAverage, startTime, recordingDuration}
        }
    }
</script>
