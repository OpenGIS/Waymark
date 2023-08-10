import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { mapData, waymarkConfig } from '@/data/eastcoasttrail.js'
// import { mapData, waymarkConfig } from '@/data/waymark.js'
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
  const visibleOverlays = ref([])
  const activeOverlay = ref(null)
  const mapHeight = ref(50)
  let modal = ref()

  function setMapHeight(heightPercent) {
    mapHeight.value = heightPercent
  }

  function setModal(m) {
    modal = m
  }

  //Actions
  function setLeafletMap(map) {
    leafletMap.value = map

    //Whenever view changes
    map.on('zoomend moveend', () => {
      const mapBounds = map.getBounds()

      //Check if overlay is visible
      visibleOverlays.value = overlays.value.filter((overlay) => {
        let contains = false

        switch (overlay.featureType) {
          case 'marker':
            //In view
            contains = mapBounds.contains(overlay.layer.getLatLng())
            break
          case 'line':
            if (contains) break

            overlay.layer.getLatLngs().forEach((element) => {
              if (mapBounds.contains(element)) {
                contains = true
              }
            })

            break
          //In view
          // return mapBounds.contains()

          case 'shape':
          //In view
          // return mapBounds.contains(overlay.layer.getLatLng())
        }

        return contains
      })
    })
  }

  function setLeafletData(dataLayer) {
    leafletData.value = dataLayer
  }

  function setLeafletReady(ready) {
    leafletReady.value = ready
  }

  function setActiveOverlay(overlay) {
    modal.value.$el.setCurrentBreakpoint(0.66)

    if (activeOverlay.value !== overlay) {
      activeOverlay.value = overlay

      leafletMap.value.setView(overlay.layer.getLatLng())
    } else {
      leafletMap.value.setView(overlay.layer.getLatLng(), 14)
    }
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

    layer.on('click', () => {
      setActiveOverlay(overlay)
    })
  }

  //Getters
  const getActiveOverlay = computed(() => {
    return activeOverlay.value
  })

  return {
    overlays,
    geoJSON,
    leafletMap,
    setLeafletMap,
    mapConfig,
    setLeafletData,
    addLayer,
    visibleOverlays,
    leafletReady,
    setLeafletReady,
    activeOverlay,
    setActiveOverlay,
    mapHeight,
    setMapHeight,
    setModal
  }
})
