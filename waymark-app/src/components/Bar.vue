<script setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import OverlayDetail from '@/components/OverlayDetail.vue'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { overlays } = storeToRefs(mapStore)
</script>

<template>
  <div id="bar">
    <div class="overlay-list">
      <OverlayDetail v-for="overlay in overlays" :overlay="overlay" />
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
