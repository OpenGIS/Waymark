<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleOverlays } = storeToRefs(mapStore)

import { layersOutline } from 'ionicons/icons'

import Detail from '@/components/Detail.vue'
import Map from '@/components/Map.vue'
import List from '@/components/List.vue'
import Bar from '@/components/Bar.vue'
</script>

<template>
  <!-- Detail -->
  <Detail />

  <!-- Map -->
  <Map />

  <!-- List -->
  <List />

  <!-- Bar -->
  <Bar />
</template>

<style></style>
