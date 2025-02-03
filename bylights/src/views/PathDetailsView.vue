<template>
    <div class="pageLayout">
        <div class="header">
            <!--icona svg per tornare al menu precedente-->
            <img :src="backIcon" alt="back icon" class="backIcon-style" @click="goBack()">
            Path details
        </div>

        <div class="content">
            <div class="path-info">
                <p class="path-date">{{ pathInfo?.path_date }}</p>
                <p class="avg-brightness-name">Average brightness:</p>
                <p class="avg-brightness" :style="{ color: getColor(calculateBrightness(pathInfo?.brightness)) }">
                    {{ calculateBrightness(pathInfo?.brightness) }} %
                </p>
            </div>
            <div class="time-info">
                <p class="path-time">Walking Time: <span style="font-style: italic;">{{ pathInfo.path_time }} minuti</span> </p>
            </div>
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
        const pathInfo = ref({});

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

        const calculateBrightness = (brightness) => {
            let maximumBrightness = 270;
            return Math.floor((100 * brightness) / maximumBrightness);
        }

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

        

        const getPathInfo = async() => {
            try {
                const response = await axios.get(
                'http://localhost/tirocinio/crowdsensing-web-app/bylights/src/db/api/getPathInfo.php',
                {
                    params: { path_id: pathId.value },
                    withCredentials: true
                }
                );

                if (response.data.success) {
                // Salva l'array di oggetti già convertito da json
                listPoints.value = response.data.recordedPoints;
                //salva tutto quello che ritorna
                pathInfo.value = response.data
                console.log("Response data:", response.data);
                console.log("pathinfo data:", pathInfo.value);
                console.log("eccoooo:", pathInfo.value.path_time);

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
                await getPathInfo();

                if (listPoints.value.length === 0) {
                    console.warn("Nessun punto registrato, impossibile centrare la mappa.");
                    return;
                }

                // Crea la mappa
                map.value = L.map(mapContainer.value, { zoomControl: false, zoomSnap: 0 });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map.value);

                // Crea i bounds per adattare la vista
                const bounds = L.latLngBounds(listPoints.value.map(p => [p.lat, p.lng]));

                // Imposta la vista per contenere tutto il percorso
                map.value.fitBounds(bounds, { padding: [50, 50] });

                loopListPoints()

                // Aggiunge la polyline colorata
                drawColoredPath();
                
            } catch (error) {
                console.error('Failed to get location:', error);
            }
        };

        onMounted(openMap);

        return { mapContainer, goBack, backIcon, getPathInfo, drawColoredPath, pathInfo, getColor, calculateBrightness };
    }
};
</script>

<style scoped>

.time-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    margin-top: 1px;
}

.path-time{
    flex-grow: 1;
    font-weight: bold;
    font-style: italic;
    margin: 0;
}

@media (max-width: 768px) {
    .path-info {
        font-size: 0.9rem; /* Riduce il font su schermi piccoli */
    }

    .path-time,
    .path-date,
    .avg-brightness-name,
    .avg-brightness {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .path-info {
        font-size: 0.8rem; /* Ulteriore riduzione su schermi molto piccoli */
    }

    .path-time
    .path-date,
    .avg-brightness-name,
    .avg-brightness {
        font-size: 0.8rem;
    }
}


.avg-brightness{
    font-weight: bold;
}

.avg-brightness-name{
    font-weight: bold;
    margin-right: 5px;
}

.path-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.path-date {
    flex-grow: 1;
    font-style: italic; 
    color: #2e2b2b;         /* un grigio medio che non sovrasta il resto */
    font-size: 0.9rem;   /* leggermente più piccola */
    letter-spacing: 0.5px; /* spaziatura delicata fra le lettere */
    opacity: 0.9;        /* un velo di trasparenza per un effetto più soft */
    font-weight: bolder;
    font-size: larger;

    margin: 0 !important;
    padding: 0 !important;
}

.header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px;
    font-weight: bolder;
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
        height: min(100vw, 400px); /* Altezza massima di 400px su schermi grandi */
        border: 3px solid #ccc;
        border-radius: 10px;
    }

    /* Schermi più piccoli */
    @media (max-width: 768px) {
        .map-box {
            height: 70vh; /* Occupa % dell'altezza dello schermo */
        }
    }

    @media (max-width: 480px) {
        .map-box {
            height: 75vh; /* Occupa % dell'altezza dello schermo per dispositivi molto piccoli */
        }
    }

    .content {
        padding-left: 20px;
        padding-right: 20px;
        font-size: 18px;
        font-weight: 500;
        text-align: left;
        color: #444;
        line-height: 1.5;
    }

</style>