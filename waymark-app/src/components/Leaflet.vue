<script setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const mapStore = useMapStore()
const { geoJSON, mapConfig } = storeToRefs(mapStore)

onMounted(() => {
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
    //Create Markers
    pointToLayer: function (feature, latlng) {
      let tData = getTypeData(getFeatureType(feature), makeKey(feature.properties.type))
      let iData = getIconData(tData)

      return L.marker(latlng, { icon: L.divIcon(iData) })
    },

    //Add to store
    onEachFeature(feature, layer) {
      mapStore.addLayer(layer)
    }
  }).addTo(map)
  mapStore.setLeafletData(dataLayer)

  //Set View
  map.fitBounds(dataLayer.getBounds())
})
</script>

<template>
  <div id="map"></div>
</template>

<style>
#map {
  height: 100%;
  width: 50%;
}
</style>
