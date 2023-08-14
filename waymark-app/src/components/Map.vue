<script setup>
import { onMounted, ref, watch, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { getTypeData, getFeatureType, getIconData } from '@/helpers/Overlay.js'
import { makeKey } from '@/helpers/Common.js'
import Marker from '@/components/Marker.vue'

//MapLibre GL
import MapLibreGL from 'maplibre-gl/dist/maplibre-gl.js'
import 'maplibre-gl/dist/maplibre-gl.css'
import { mapboxStyle } from '@/assets/js/style.js'

const mapStore = useMapStore()
const { geoJSON, mapHeight, visibleOverlays, overlays } = storeToRefs(mapStore)

let map = null

const dataBounds = new MapLibreGL.LngLatBounds()

// ==== Computed ====

const pointsFeatures = computed(() => {
  return geoJSON.value.features.filter((feature) => {
    return feature.geometry.type === 'Point'
  })
})

const linesFeatures = computed(() => {
  return geoJSON.value.features.filter((feature) => {
    return ['LineString', 'MultiLineString'].indexOf(feature.geometry.type) !== -1
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

// watch(mapHeight, () => {
//   setTimeout(() => {
//     // leafletMap.invalidateSize(false)
//   }, 201)
// })

// ==== Mounted ====

onMounted(() => {
  //Create Map
  map = new MapLibreGL.Map({
    container: 'map',
    style: mapboxStyle,
    center: [-52.75, 47.3], // starting position [lng, lat]
    zoom: 9, // starting zoom,
    maxZoom: 16
  }).once('load', () => {
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
      const marker = new MapLibreGL.Marker({
        element: el,
        offset: iconData.iconAnchor
      })

      marker.setLngLat(feature.geometry.coordinates)
      marker.addTo(map)

      //Extend bounds
      dataBounds.extend(feature.geometry.coordinates)

      const overlay = mapStore.addMarker(marker, feature)

      el.addEventListener('click', () => {
        mapStore.setActiveOverlay(overlay)
      })
    })

    //Lines
    const dataSource = map.addSource('geoJSON', {
      type: 'geojson',
      data: geoJSON.value
    })

    const dataLayer = map.addLayer({
      id: 'geoJSON',
      type: 'line',
      source: 'geoJSON',
      paint: {
        'line-color': '#088',
        'line-width': 2
      }
    })

    //Extend bounds
    linesFeatures.value.forEach((feature) => {
      for (let i in feature.geometry.coordinates) {
        dataBounds.extend(feature.geometry.coordinates[i])
      }
    })

    //Update Visible whenever view changes
    map.on('zoomend', updateVisibleOverlays).on('moveend', updateVisibleOverlays)

    //Set initial centre and zoom to it
    map.setCenter(dataBounds.getCenter())
    map.fitBounds(dataBounds, { padding: 20 })

    map.once('moveend', () => {
      //Set Max bounds
      map.setMaxBounds(map.getBounds())
    })
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
  width: 100%;
}
</style>
