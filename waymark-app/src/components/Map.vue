<script setup>
import { onMounted, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'

//Mapbox GL
import maplibregl from 'maplibre-gl/dist/maplibre-gl.js'
import { mapboxStyle } from '@/assets/js/style.js'

const mapStore = useMapStore()
const { geoJSON, mapConfig, mapHeight } = storeToRefs(mapStore)

const map = ref()
let leafletMap = null

watch(mapHeight, () => {
  setTimeout(() => {
    // leafletMap.invalidateSize(false)
  }, 201)
})

onMounted(() => {
  //Create Map
  var map = new maplibregl.Map({
    container: 'map',
    style: mapboxStyle,
    center: [-52.75, 47.3], // starting position [lng, lat]
    zoom: 9 // starting zoom
  })

  // mapStore.setLeafletMap(leafletMap)

  map.on('load', () => {
    map.addSource('geoJSON', {
      type: 'geojson',
      data: geoJSON.value
    })

    map.addLayer({
      id: 'geoJSON',
      type: 'line',
      source: 'geoJSON',
      paint: {
        'line-color': '#088',
        'line-width': 2
      }
    })
  })

  //     let typeData = getTypeData(getFeatureType(feature), makeKey(feature.properties.type))
  //     let iconData = getIconData(typeData)

  //Set View
  setTimeout(() => {
    // leafletMap.fitBounds(dataLayer.getBounds())
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