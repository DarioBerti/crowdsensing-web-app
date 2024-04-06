<template>
    <button @click="getLocation()" >Get Location</button>
    <p>{{ lat }},{{ lng }}</p>

    <div ref="mapContainer" style="height: 400px;"></div>
</template>

<script>
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

            const getLocation = () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        lat.value = position.coords.latitude
                        lng.value = position.coords.longitude
                    })
                }
            }

            const openMap = () => {
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

<style scoped>
</style>