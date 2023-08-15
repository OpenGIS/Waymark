<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleOverlays } = storeToRefs(mapStore)

import Detail from '@/components/Detail.vue'
import Map from '@/components/Map.vue'
import Bar from '@/components/Bar.vue'
</script>

<template>
  <!-- Map -->
  <Map />

  <!-- Detail -->
  <Detail />

  <!-- Bar -->
  <Bar />
</template>

<style></style>
