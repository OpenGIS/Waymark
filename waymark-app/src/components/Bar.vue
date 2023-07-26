<script setup>
import { onMounted, computed } from 'vue'
import { storeToRefs } from 'pinia'
import OverlayDetail from '@/components/OverlayDetail.vue'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { overlays } = storeToRefs(mapStore)

const byType = (overlays) => {
  const byType = []

  for (let i in overlays) {
    let overlay = overlays[i]

    if (typeof byType[overlay.typeKey] !== 'object') {
      byType[overlay.typeKey] = []
    }

    byType[overlay.typeKey].push(overlay)
  }

  return byType
}

const markers = computed(() => {
  return byType(
    overlays.value.filter((o) => {
      return o.featureType === 'marker'
    })
  )
})

onMounted(() => {
  console.log(markers.value)

  // for (let i in overlays) {
  //   let overlay = overlays[i]

  //   // if (typeof overlaysByType[overlay.typeKey] === 'array') {
  //   //   overlaysByType[overlay.typeKey].push(overlay)
  //   // } else {
  //   //   overlaysByType[overlay.typeKey] = [overlay]
  //   // }
  // }
})

// setTimeout(() => {
//   overlays.value = []
// }, 2000)
</script>

<template>
  <div id="bar">
    <div class="overlay-list">
      <!-- Markers -->
      <div class="marker-list">
        <OverlayDetail v-for="overlay in markers" :overlay="overlay" />
      </div>
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
vue
