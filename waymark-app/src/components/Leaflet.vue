<script setup>
import { onMounted, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const mapStore = useMapStore()
const { geoJSON, mapConfig, mapHeight } = storeToRefs(mapStore)

const map = ref()
let leafletMap = null

watch(mapHeight, () => {
  setTimeout(() => {
    leafletMap.invalidateSize(false)
  }, 201)
})

onMounted(() => {
  //Create Map
  leafletMap = L.map('map', {
    centre: [40, 40],
    zoom: 12,
    zoomControl: false
  })
  mapStore.setLeafletMap(leafletMap)

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
  }).addTo(leafletMap)

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
  }).addTo(leafletMap)
  mapStore.setLeafletData(dataLayer)

  //Set View
  setTimeout(() => {
    leafletMap.fitBounds(dataLayer.getBounds())
    mapStore.setLeafletReady(true)
  }, 500)
})
</script>

<template>
  <div id="map" ref="map" :style="`height:${mapHeight}%`"></div>
</template>

<style>
#map {
  height: 100%;
  /*  width: 50%;*/
}
</style>
