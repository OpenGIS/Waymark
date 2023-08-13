<script setup>
import { onMounted, ref, watch, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'

//MapLibre GL
import maplibregl from 'maplibre-gl/dist/maplibre-gl.js'
import 'maplibre-gl/dist/maplibre-gl.css'
import { mapboxStyle } from '@/assets/js/style.js'

const mapStore = useMapStore()
const { geoJSON, mapConfig, mapHeight } = storeToRefs(mapStore)

const map = ref()
let leafletMap = null

// ==== Computed ====

const pointsFeatures = computed(() => {
  return geoJSON.value.features.filter((feature) => {
    return feature.geometry.type === 'Point'
  })
})

// ==== Watchers ====

watch(mapHeight, () => {
  setTimeout(() => {
    // leafletMap.invalidateSize(false)
  }, 201)
})

// ==== Mounted ====

onMounted(() => {
  console.log(geoJSON.value)
  //Create Map
  var map = new maplibregl.Map({
    container: 'map',
    style: mapboxStyle,
    center: [-52.75, 47.3], // starting position [lng, lat]
    zoom: 9, // starting zoom,
    maxZoom: 16
  })

  // mapStore.setLeafletMap(leafletMap)

  pointsFeatures.value.forEach((feature) => {
    const typeData = getTypeData(getFeatureType(feature), makeKey(feature.properties.type))
    const iconData = getIconData(typeData)

    // create a DOM element for the marker
    const el = document.createElement('div')
    el.className = iconData.className
    el.innerHTML = iconData.html
    el.style.width = `${iconData.iconSize[0]}px`
    el.style.height = `${iconData.iconSize[1]}px`

    el.addEventListener('click', () => {
      console.log(feature)
    })

    // add marker to map
    new maplibregl.Marker({
      element: el,
      offset: iconData.iconAnchor
    })
      .setLngLat(feature.geometry.coordinates)
      .addTo(map)
  })

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
