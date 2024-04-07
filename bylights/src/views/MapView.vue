<template>
    <!--contenitore parent che contiene elementi che si sovrappongono-->
    <div class = "mapLayout">
        <div ref="mapContainer" class="full-screen-map" ></div>
        <div class="record-icon">
            <!--binding per svg-->
            <img :src="recordIcon" alt="record icon" class = "record-style">
        </div>
    </div>
    
</template>

<style scoped>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
    }
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
    }
    .record-style{
        height: 10%;
        width: 10%;
    }
    @media (max-width: 600px) {
        .record-style{
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

    export default{
        name: 'MapView',

        setup(){
            const lat = ref(0)
            const lng = ref(0)
            const map = ref()
            const mapContainer = ref()
            const defaultIcon = new Icon({
                iconUrl: require('leaflet/dist/images/marker-icon.png'), 
                shadowUrl: require('leaflet/dist/images/marker-shadow.png')
            });

            L.Marker.prototype.options.icon = defaultIcon;

            const getLocation = () => {
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition((position) => {
                        lat.value = position.coords.latitude
                        lng.value = position.coords.longitude
                        map.value.setView([lat.value, lng.value], 16)

                        L.marker([lat.value, lng.value]).addTo(map.value);
                    })
                }
            }

            const openMap = () => {
                //legge la posizione iniziale e set di latitudine, lungitudine e marker iniziale
                getLocation();

                //set del map.value
                map.value = L.map(mapContainer.value, {zoomControl: false}).setView([51.505, -0.09], 13);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map.value);
            }

            onMounted(openMap)

            return{lat, lng, getLocation, map, mapContainer, openMap, recordIcon}
        }
    }
</script>
