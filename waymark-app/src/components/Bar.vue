<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays } = storeToRefs(mapStore)

const toggleList = () => {
  listOpen = !listOpen
}
</script>

<template>
  <!-- Icon -->
  <div id="bar">
    <div class="button" @click="mapStore.toggleList()">
      {{ visibleOverlays.length }}
      <i class="ion ion-layers-outline"></i>
    </div>
  </div>
</template>

<style lang="less">
#bar {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 60px;
  width: 100%;
  background: red;
  .button {
    margin: 10px;
    padding: 10px;
    float: right;
    color: white;
    background: blue;
  }
}
</style>
