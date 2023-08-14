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
  const uIStatus = ref({
    detail: 0,
    map: 6,
    list: 0
  })

  //Actions
  function setMap(m) {
    map.value = m
  }

  function toggleList() {
    uIStatus.value.list = uIStatus.value.list + 2
    uIStatus.value.map = uIStatus.value.map - 2
  }

  function setActiveOverlay(overlay) {
    if (!uIStatus.value.detail) {
      console.log(overlay)
      uIStatus.value.detail = uIStatus.value.detail + 1
      uIStatus.value.map = uIStatus.value.map - 1
    }

    if (activeOverlay.value !== overlay) {
      activeOverlay.value = overlay

      map.value.setCenter(overlay.marker.getLngLat())
    } else {
      map.value.setZoom(14)
    }
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

  const detailHeight = computed(() => {
    return (uIStatus.value.detail / 6) * 100
  })

  const mapHeight = computed(() => {
    return (uIStatus.value.map / 6) * 100
  })

  const listHeight = computed(() => {
    return (uIStatus.value.list / 6) * 100
  })

  return {
    overlays,
    geoJSON,
    map,
    setMap,
    mapConfig,
    // addLayer,
    visibleOverlays,
    activeOverlay,
    setActiveOverlay,
    detailHeight,
    mapHeight,
    listHeight,
    addMarker,
    toggleList
  }
})
