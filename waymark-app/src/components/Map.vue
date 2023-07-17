<script setup>
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
// import { useMapStore } from '@/stores/mapStore.js'
import Leaflet from '@/components/Leaflet.vue'
// onMounted(() => {
//   const mapStore = useMapStore()
//   const { geoJSON } = storeToRefs(mapStore)
//   mapStore.init()
// })
</script>

<template>
  <Leaflet />
</template>
