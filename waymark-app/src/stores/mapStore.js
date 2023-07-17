import { ref } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/waymark.js'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

export const useMapStore = defineStore('map', () => {
  let geoJSON = ref(mapData)
  let mapConfig = ref(waymarkConfig)
  let leafletMap = ref({})

  function setLeafletMap(map) {
    leafletMap = map
  }

  return {
    geoJSON,
    leafletMap,
    setLeafletMap,
    mapConfig
  }
})
