<script setup>
import { onMounted, ref, watch, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'
import Marker from '@/components/Marker.vue'

//MapLibre GL
import maplibregl from 'maplibre-gl/dist/maplibre-gl.js'
import 'maplibre-gl/dist/maplibre-gl.css'
import { mapboxStyle } from '@/assets/js/style.js'

const mapStore = useMapStore()
const { geoJSON, mapConfig, mapHeight, visibleOverlays, overlays } = storeToRefs(mapStore)

let map = null

// ==== Computed ====

const pointsFeatures = computed(() => {
  return geoJSON.value.features.filter((feature) => {
    return feature.geometry.type === 'Point'
  })
})

const updateVisibleOverlays = () => {
  const mapBounds = map.getBounds()

  //Check if overlay is visible
  visibleOverlays.value = overlays.value.filter((overlay) => {
    let contains = false

    switch (overlay.featureType) {
      case 'marker':
        //In view
        contains = mapBounds.contains(overlay.marker.getLngLat())

        break
      // case 'line':
      //   if (contains) break

      //   overlay.layer.getLatLngs().forEach((element) => {
      //     if (mapBounds.contains(element)) {
      //       contains = true
      //     }
      //   })

      //   break
      //In view
      // return mapBounds.contains()

      // case 'shape':
      //In view
      // return mapBounds.contains(overlay.layer.getLatLng())
    }

    return contains
  })
}

// ==== Watchers ====

watch(mapHeight, () => {
  setTimeout(() => {
    // leafletMap.invalidateSize(false)
  }, 201)
})

// ==== Mounted ====

onMounted(() => {
  //Create Map
  map = new maplibregl.Map({
    container: 'map',
    style: mapboxStyle,
    center: [-52.75, 47.3], // starting position [lng, lat]
    zoom: 9, // starting zoom,
    maxZoom: 16
  }).on('load', () => {
    //Markers
    pointsFeatures.value.forEach((feature) => {
      const typeData = getTypeData(getFeatureType(feature), makeKey(feature.properties.type))
      const iconData = getIconData(typeData)

      // create a DOM element for the marker
      const el = document.createElement('div')
      el.className = iconData.className
      el.innerHTML = iconData.html
      el.style.width = `${iconData.iconSize[0]}px`
      el.style.height = `${iconData.iconSize[1]}px`

      // add marker to map
      const marker = new maplibregl.Marker({
        element: el,
        offset: iconData.iconAnchor
      })

      marker.setLngLat(feature.geometry.coordinates)
      marker.addTo(map)

      const overlay = mapStore.addMarker(marker, feature)

      el.addEventListener('click', () => {
        mapStore.setActiveOverlay(overlay)
      })
    })

    //Lines
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

    //Update Visible whenever view changes
    map.on('zoomend', updateVisibleOverlays).on('moveend', updateVisibleOverlays)
  })

  mapStore.setMap(map)
})
</script>

<template>
  <div id="map" :style="`height:${mapHeight}%`"></div>
</template>

<style>
#map {
  height: 100%;
  /*  width: 50%;*/
}
</style>
