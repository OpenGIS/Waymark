<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays, barOpen } = storeToRefs(mapStore)
import List from '@/components/List.vue'
</script>

<template>
  <div id="bar" :style="`height:${barOpen * 50}%`">
    <!-- Nav -->
    <nav id="bar-nav">
      <div class="button" @click="mapStore.toggleBar()">
        {{ visibleOverlays.length }}
        <i class="ion ion-layers-outline"></i>
      </div>
    </nav>

    <!-- Content -->
    <div class="bar-content">
      <!-- List -->
      <List />
    </div>
  </div>
</template>

<style lang="less">
#bar {
  position: absolute;
  bottom: 0;
  left: 0;
  min-height: 60px;
  width: 100%;
  background: rgba(249, 249, 249, 0.5);
  .button {
    margin: 10px;
    padding: 10px;
    float: right;
    color: white;
    background: blue;
  }
}
</style>
