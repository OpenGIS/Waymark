import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/data.js'

import { getTypeData, getFeatureType } from '@/helpers/Overlay.js'

export const useMapStore = defineStore('map', () => {
  //State
  const geoJSON = ref(mapData)
  const mapConfig = ref(waymarkConfig)
  const map = ref({})
  const overlays = ref([])
  const visibleOverlays = ref([])
  const activeOverlay = ref({})

  const barOpen = ref(false)
  const detailOpenness = ref(0)

  //Actions
  function setMap(m) {
    map.value = m
  }

  function toggleBar() {
    barOpen.value = !barOpen.value
  }

  function changeDetailOpen(openness) {
    detailOpenness.value = openness
  }

  function setActiveOverlay(overlay) {
    //Overlay already open
    if (activeOverlay.value && activeOverlay.value.id == overlay.id) {
      //Zoom to
      map.value.setCenter(overlay.marker.getLngLat())
      map.value.setZoom(14)

      //Increase info
      detailOpenness.value++

      //Update
    } else {
      activeOverlay.value = overlay

      //Ensure displayed
      if (!detailOpenness.value) {
        detailOpenness.value = 1
      }
    }

    // if (activeOverlay.value !== overlay) {
    //   activeOverlay.value = overlay

    //   map.value.setCenter(overlay.marker.getLngLat())
    // } else {
    //   map.value.setZoom(14)
    // }

    // detailOpenness.value = true

    // console.log(activeOverlay.value.id)

    // //Change
    // if (activeOverlay.value.id !== overlay.id) {
    //   activeOverlay.value = overlay
    //   //Focus
    // } else {
    //   map.value.setCenter(overlay.marker.getLngLat())
    //   map.value.setZoom(14)
    // }
  }

  function addMarker(marker, feature) {
    let featureType = 'marker'
    let typeKey = feature.properties.type

    let overlay = {
      id: overlays.value.length + 1,
      typeKey: typeKey,
      typeData: getTypeData('marker', typeKey),
      feature: feature,
      marker: marker,
      featureType: featureType,
      element: marker.getElement()
    }

    overlays.value.push(overlay)

    return overlay
  }

  function setCenter(coords) {
    map.value.setCenter(coords)
    map.value.setZoom(14)
  }

  //Getters
  const getActiveOverlay = computed(() => {
    return activeOverlay.value
  })

  return {
    overlays,
    geoJSON,
    map,
    setMap,
    mapConfig,
    visibleOverlays,
    activeOverlay,
    setActiveOverlay,
    detailOpenness,
    changeDetailOpen,
    barOpen,
    addMarker,
    toggleBar,
    setCenter
  }
})
