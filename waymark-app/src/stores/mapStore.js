import { ref } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/waymark.js'
import L from 'leaflet'
import { getTypeData, getFeatureType } from '@/helpers/Overlay.js'

export const useMapStore = defineStore('map', () => {
  let geoJSON = ref(mapData)
  let mapConfig = ref(waymarkConfig)
  let leafletMap = ref()
  let leafletData = ref()
  let overlays = ref([])

  function setLeafletMap(map) {
    leafletMap.value = map
  }

  function setLeafletData(dataLayer) {
    leafletData.value = dataLayer
  }

  function addLayer(layer) {
    let featureType = getFeatureType(layer.feature)
    let typeKey = layer.feature.properties.type

    let overlay = {
      id: L.stamp(layer),
      typeKey: typeKey,
      typeData: getTypeData(featureType, typeKey),
      feature: layer.feature,
      layer: layer,
      featureType: featureType
    }

    overlays.value.push(overlay)
  }

  return {
    overlays,
    geoJSON,
    leafletMap,
    setLeafletMap,
    mapConfig,
    setLeafletData,
    addLayer
  }
})
