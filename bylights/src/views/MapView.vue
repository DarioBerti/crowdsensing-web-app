<template>
    <!--contenitore parent che contiene elementi che si sovrappongono-->
    <div class = "mapLayout">
        <div ref="mapContainer" class="full-screen-map" ></div>
        <div class="record-icon">
                <div v-if="changeFlag">
                    <!--binding per svg-->
                    <img :src="recordIcon" alt="record icon" class = "record-style" @click="switchRecording">
                </div>
                <div v-else>
                    <!--binding per svg-->
                    <img :src="stopRecordIcon" alt="stop record icon" class = "stop-record-style" @click="switchRecording">
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
    import { Icon } from 'leaflet'
    import 'leaflet/dist/leaflet.css'
    import {onMounted, ref} from 'vue'
    import L from 'leaflet'
    import recordIcon from '../assets/record-svg.svg'
    import stopRecordIcon from '../assets/stop-record-svg.svg'
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

            const created = async() => {
                axios.get('http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/user.php', {
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

            const defaultIcon = new Icon({
                iconUrl: require('leaflet/dist/images/marker-icon.png'),
                shadowUrl: require('leaflet/dist/images/marker-shadow.png')
            });
            const changeFlag = ref(true)

            L.Marker.prototype.options.icon = defaultIcon;

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
                    await getLocation()

                    //set del map.value
                    map.value = L.map(mapContainer.value, {zoomControl: false}).setView([lat.value, lng.value], 16);
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map.value);

                    //aggiunta marker
                    L.marker([lat.value, lng.value]).addTo(map.value);


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

            onMounted(() => {
                created();
                openMap();
            })



            return{lat, lng, getLocation, map, mapContainer, openMap, recordIcon, changeFlag, switchRecording, stopRecordIcon, created, user}
        }
    }
</script>
