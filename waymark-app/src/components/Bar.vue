<script setup>
import { onMounted, computed } from 'vue'
import { storeToRefs } from 'pinia'
import TypeList from '@/components/TypeList.vue'
import { useMapStore } from '@/stores/mapStore.js'
import { overlaysByType } from '@/helpers/Overlay.js'

const mapStore = useMapStore()
const { overlays } = storeToRefs(mapStore)

const markersByType = computed(() => {
  return overlaysByType(
    overlays.value.filter((o) => {
      return o.featureType === 'marker'
    })
  )
})

const linesByType = computed(() => {
  return overlaysByType(
    overlays.value.filter((o) => {
      return o.featureType === 'line'
    })
  )
})

const shapesByType = computed(() => {
  return overlaysByType(
    overlays.value.filter((o) => {
      return o.featureType === 'shape'
    })
  )
})
</script>

<template>
  <div id="bar">
    <div class="type-lists">
      <!-- Markers -->
      <TypeList class="marker-list" :overlaysByType="markersByType" />

      <!-- Lines -->
      <TypeList class="line-list" :overlaysByType="linesByType" />

      <!-- Shapes -->
      <TypeList class="shape-list" :overlaysByType="shapesByType" />
    </div>
  </div>
</template>
<style>
#bar {
  height: 100%;
  width: 50%;
  overflow-y: scroll;
}
</style>
