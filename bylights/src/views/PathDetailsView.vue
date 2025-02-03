<template>
    <div class="pageLayout">
        <div class="header">
            <!--icona svg per tornare al menu precedente-->
            <img :src="backIcon" alt="back icon" class="backIcon-style" @click="goBack()">
            Path details
        </div>

        <div class="content">
            <p></p>
        </div>


        <div class="map-container">
            <div ref="mapContainer" class="map-box"></div>
        </div>


    </div>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import { onMounted, ref } from 'vue';
import L from 'leaflet';
import backIcon from '@/assets/right-arrow-svg.svg'
import axios from 'axios'
import { useRouter } from 'vue-router'


export default {
    props: ['path_id'],
    name: 'SimpleMap',
    setup(props) {
        const map = ref();
        const router = useRouter();
        const mapContainer = ref();
        const listPoints = ref([]);
        const error = ref(null);
        const pathId = ref(props.path_id); 

        const drawColoredPath = () => {
            // Controlla che ci siano almeno 2 punti
            if (listPoints.value.length < 2) return;

            // Ciclo sui punti a coppie [p1, p2]
            for (let i = 0; i < listPoints.value.length - 1; i++) {
                const p1 = listPoints.value[i];
                const p2 = listPoints.value[i + 1];

                // Scegli il colore in base alla brightness di p1 (o media di p1 e p2)
                let colorSegment = 'green';
                if (p1.brightness < 90) {
                colorSegment = 'red';
                } else if (p1.brightness < 120) {
                colorSegment = 'orange';
                }

                // Crea polyline tra p1 e p2
                L.polyline([[p1.lat, p1.lng], [p2.lat, p2.lng]], {
                color: colorSegment,
                weight: 6,      // spessore linea
                opacity: 0.7,   // trasparenza generale
                }).addTo(map.value);
            }
            };

        

        const getPathPoints = async() => {
            try {
                const response = await axios.get(
                'http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/getPathPoints.php',
                {
                    params: { path_id: pathId.value },
                    withCredentials: true
                }
                );

                console.log("Response data:", response.data);

                if (response.data.success) {
                // Salva l'array di oggetti già convertito da json
                listPoints.value = response.data.recordedPoints;
                error.value = false;

                } else {
                error.value = true;
                console.log("Error message from server:", response.data.message);
                router.push('/mapView');
                }
            } catch(err) {
                // Errore, reindirizza alla mappa
                error.value = true;
                console.log("errore catch");
                router.push('/mapView');
            }
            }

        const goBack = () => {
            window.history.back()
        }

        const drawPoint = (point) => {
            const color = ref(null)

            if (point.brightness < 90){
                color.value = 'red'
            }else if(point.brightness < 120){
                color.value = 'orange'
            }else{
                color.value = 'green'
            }

            L.circle([point.lat, point.lng], {
                color: color.value,
                fillColor: color.value,
                fillOpacity: 0.5,
                radius: 6
            }).addTo(map.value);
        }

        //DA CHIAMARE DOPO LA RESPONSE
        const loopListPoints = () => {
            //cicla ogni oggetto della lista listPoints
            listPoints.value.forEach(point => {
                //aggiunge alla mappa ogni singolo punto
                drawPoint(point)
            });
        }


        const openMap = async () => {
            try {
                // Prima recuperi i punti
                await getPathPoints();

                // 2) Scegli la posizione di partenza
                let startLat = 44.144201;   // fallback
                let startLng = 12.250434;   // fallback

                if (listPoints.value.length > 0) {
                // Prende il primo punto di listPoints
                    let length = listPoints.value.length;
                    //calcola punto a metà dell'array dei punti per settare view in modo centrale
                    let half = Math.round(length/2);
                    console.log("metà: ",half)
                    startLat = listPoints.value[half].lat;
                    startLng = listPoints.value[half].lng;
                    console.log("primo punto: ", startLat);
                }

                map.value = L.map(mapContainer.value,  {zoomControl: false, zoomSnap: 0}).setView([startLat, startLng], 16.5);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map.value);

                loopListPoints()

                // Aggiunge la polyline colorata
                drawColoredPath();
                
            } catch (error) {
                console.error('Failed to get location:', error);
            }
        };

        onMounted(openMap);

        return { mapContainer, goBack, backIcon, getPathPoints, drawColoredPath };
    }
};
</script>

<style scoped>

.header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px;
    font-weight: bold;
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #333;
    position: relative;
}

/* Per la freccia, la posizioniamo a sinistra */
.backIcon-style {
    position: absolute;
    left: 15px;
    width: 20px;
    height: 20px;
    cursor: pointer;
    transform: scaleX(-1);
}


    .page-layout {
        position: relative;
        min-height: 100vh;
    }

    .map-container {
        position: fixed;
        bottom: 15px;
        padding: 0 20px;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: center; 
        box-sizing: border-box;
    }

    .map-box {
        width: 100%;
        height: min(100vw, 400px); 
        border: 3px solid #ccc;
        border-radius: 10px;

    }
    .content {
        padding: 20px;
        font-size: 18px;
        font-weight: 500;
        text-align: left;
        color: #444;
        line-height: 1.5;
    }

</style>