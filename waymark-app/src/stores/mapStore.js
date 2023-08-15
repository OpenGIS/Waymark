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
  const activeOverlay = ref(null)

  const barOpen = ref(false)
  const detailOpen = ref(false)
  const detailExpanded = ref(false)

  //Actions
  function setMap(m) {
    map.value = m
  }

  function toggleBar() {
    barOpen.value = !barOpen.value
  }

  function toggleDetail() {
    detailOpen.value = !detailOpen.value
  }

  function setActiveOverlay(overlay) {
    console.log('setActiveOverlay')
    activeOverlay.value = overlay

    detailOpen.value = true

    // if (activeOverlay.value !== overlay) {
    //   activeOverlay.value = overlay

    //   map.value.setCenter(overlay.marker.getLngLat())
    // } else {
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

  // function addLayer(layer) {
  //   let featureType = getFeatureType(layer.feature)
  //   let typeKey = layer.feature.properties.type

  //   let overlay = {
  //     id: overlayIndex,
  //     typeKey: typeKey,
  //     typeData: getTypeData(featureType, typeKey),
  //     feature: layer.feature,
  //     layer: layer,
  //     featureType: featureType
  //   }

  //   overlays.value.push(overlay)

  //   overlayIndex++
  // }

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
    toggleDetail,
    visibleOverlays,
    activeOverlay,
    setActiveOverlay,
    detailOpen,
    detailExpanded,
    barOpen,
    addMarker,
    toggleBar
  }
})
