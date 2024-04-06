<template>
    <div ref="mapContainer" class="full-screen-map" ></div>
</template>

<style scoped>
    .full-screen-map {
        width: 100vw;  /* 100% della larghezza della viewport */
        height: 100vh; /* 100% dell'altezza della viewport */
        position: fixed; /* Opzionale: per rendere la mappa posizionata sopra tutto */
        top: 0;
        left: 0;
    }
</style>

<script>
    import 'leaflet/dist/leaflet.css';
    import { Icon } from 'leaflet';
    import 'leaflet/dist/leaflet.css';
    import {onMounted, ref} from 'vue'
    import L from 'leaflet'

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

                //set della mappa
                map.value = L.map(mapContainer.value).setView([51.505, -0.09], 13);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map.value);
            }

            onMounted(openMap)

            return{lat, lng, getLocation, map, mapContainer, openMap}
        }
    }
</script>
