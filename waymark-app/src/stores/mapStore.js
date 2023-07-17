import { ref } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/waymark.js'

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

  function addOverlay(overlay) {
    overlays.value.push(overlay)
  }

  return {
    overlays,
    geoJSON,
    leafletMap,
    setLeafletMap,
    mapConfig,
    setLeafletData,
    addOverlay
  }
})
