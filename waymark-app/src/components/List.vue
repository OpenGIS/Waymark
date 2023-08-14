<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import TypeList from '@/components/TypeList.vue'
import { overlaysByType } from '@/helpers/Overlay.js'

import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays } = storeToRefs(mapStore)

const activeType = ref('marker')

const activeOverlays = computed(() => {
  return overlaysByType(
    visibleOverlays.value.filter((o) => {
      return o.featureType === activeType.value
    })
  )
})
</script>

<template>
  <div id="list">
    <!-- Type Nav -->
    <div :value="activeType">
      <div @click="activeType = 'marker'">
        <i class="ion ion-location-outline"></i>
      </div>
      <div @click="activeType = 'line'">
        <i class="ion ion-analytic-outline"></i>
      </div>
      <div @click="activeType = 'shape'">
        <i class="ion ion-shapes-outline"></i>
      </div>
    </div>

    <!-- Overlays (by Type) -->
    <TypeList :overlaysByType="activeOverlays" />
  </div>
</template>
