<template>
    <div class="mapLayout">
        <div ref="mapContainer" class="full-screen-map"></div>
    </div>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import { onMounted, ref } from 'vue';
import L from 'leaflet';

export default {
    props: ['path_id'],
    name: 'SimpleMap',
    setup(props) {
        const start_lat = ref(0);
        const start_lng = ref(0);
        const map = ref();
        const mapContainer = ref();
        const listPoints = ref([])

        console.log(props.path_id)

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
                radius: 8
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

        const setStartingLocation = async() => {
            start_lat.value =  44.143333
            start_lng.value =  12.249722
        }

        const openMap = async () => {
            try {
                await setStartingLocation();

                map.value = L.map(mapContainer.value,  {zoomControl: false}).setView([start_lat.value, start_lng.value], 16);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map.value);


                //INIZIALIZZA LISTA DI PUNTI PER PROVA
                listPoints.value.push({ lat: 44.143333, lng: 12.249722, brightness: 150 });
                listPoints.value.push({ lat: 44.143550, lng: 12.249900, brightness: 145 });
                listPoints.value.push({ lat: 44.143767, lng: 12.250078, brightness: 100 });
                listPoints.value.push({ lat: 44.143984, lng: 12.250256, brightness: 100 });
                listPoints.value.push({ lat: 44.144201, lng: 12.250434, brightness: 90 });
                listPoints.value.push({ lat: 44.144418, lng: 12.250612, brightness: 80 });

                loopListPoints()

            } catch (error) {
                console.error('Failed to get location:', error);
            }
        };

        onMounted(openMap);

        return { mapContainer };
    }
};
</script>

<style scoped>
.full-screen-map {
    height: 100vh;
    width: 100%;
}
.mapLayout {
    position: relative;
    height: 100vh;
}
</style>
