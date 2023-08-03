import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/waymark.js'
import L from 'leaflet'
import { getTypeData, getFeatureType } from '@/helpers/Overlay.js'

export const useMapStore = defineStore('map', () => {
  //State
  const geoJSON = ref(mapData)
  const mapConfig = ref(waymarkConfig)
  const leafletMap = ref()
  const leafletData = ref()
  const overlays = ref([])
  const leafletReady = ref(false)
  const visibleMarkers = ref([])

  //Actions
  function setLeafletMap(map) {
    leafletMap.value = map

    map.on('zoomend moveend', () => {
      visibleMarkers.value = overlays.value.filter((overlay) => {
        //Markers Only
        if (overlay.featureType != 'marker') return false

        // console.log(leafletMap.value.getBounds().contains(overlay.layer.getLatLng()))

        //In view
        return map.getBounds().contains(overlay.layer.getLatLng())
      })
    })
  }

  function setLeafletData(dataLayer) {
    leafletData.value = dataLayer
  }

  function setLeafletReady(ready) {
    leafletReady.value = ready
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

  //Getters
  // const visibleMarkers = computed(() => {
  //   if (!leafletReady.value) {
  //     return []
  //   }
  //   if (typeof leafletMap.value !== 'object') return []

  //   return overlays.value.filter((overlay) => {
  //     //Markers Only
  //     if (overlay.featureType != 'marker') return false

  //     // console.log(leafletMap.value.getBounds().contains(overlay.layer.getLatLng()))

  //     //In view
  //     return leafletMap.value.getBounds().contains(overlay.layer.getLatLng())
  //   })
  // })

  return {
    overlays,
    geoJSON,
    leafletMap,
    setLeafletMap,
    mapConfig,
    setLeafletData,
    addLayer,
    visibleMarkers,
    leafletReady,
    setLeafletReady
  }
})
