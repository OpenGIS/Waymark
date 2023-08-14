<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays } = storeToRefs(mapStore)
</script>

<template>
  <!-- Icon -->
  <div id="bar">
    <div class="button">
      {{ visibleOverlays.length }}
      <i class="ion ion-layers-outline"></i>
    </div>
  </div>
</template>
