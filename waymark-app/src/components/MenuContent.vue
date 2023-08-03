<script setup>
import { ref, computed, onMounted } from 'vue'
import TypeList from '@/components/TypeList.vue'
import { overlaysByType } from '@/helpers/Overlay.js'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { overlays, leafletMap, visibleMarkers } = storeToRefs(mapStore)

let activeType = ref('marker')

const activeOverlays = computed(() => {
  return overlaysByType(
    visibleMarkers.value.filter((o) => {
      // if (o.featureType == 'marker') {
      //   if (!leafletMap.value.getBounds().contains(o.layer.getLatLng())) {
      //     return false
      //   }
      // }

      return o.featureType === activeType.value
    })
  )
})
</script>

<template>
  <nav>
    <div @click="activeType = 'marker'">Markers</div>
    <div @click="activeType = 'line'">Lines</div>
    <div @click="activeType = 'shape'">Shapes</div>
  </nav>

  <div class="type-lists">
    <!-- Markers -->
    <TypeList :overlaysByType="activeOverlays" />
  </div>
</template>

<style lang="less">
#bar {
  nav {
    display: flex;
    div {
      width: 50px;
      height: 50px;
      background: blue;
    }
  }
}
</style>
