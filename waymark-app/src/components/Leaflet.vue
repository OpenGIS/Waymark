<script setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

onMounted(() => {
  const mapStore = useMapStore()
  const { geoJSON, mapConfig } = storeToRefs(mapStore)

  //Create Map
  const map = L.map('map')
  mapStore.setLeafletMap(map)

  //Tile Layer
  let tileURL = 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
  let tileAttr = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  if (typeof mapConfig.value.tile_layers[0] === 'object') {
    let tileLayer = mapConfig.value.tile_layers[0]
    tileURL = tileLayer['layer_url']
    tileAttr = tileLayer['layer_attribution']
  }
  L.tileLayer(tileURL, {
    maxZoom: 17,
    attribution: tileAttr
  }).addTo(map)

  //Data Layer
  const dataLayer = L.geoJSON(geoJSON.value, {
    // pointToLayer: function (feature, latlng) {
    //   return L.divIcon(latlng)
    // }
  }).addTo(map)

  //Set View
  map.fitBounds(dataLayer.getBounds())
})
</script>

<template>
  <div id="map"></div>
</template>

<style>
#map {
  width: 100%;
  height: 100%;
  /*  background: red;*/
}
</style>
