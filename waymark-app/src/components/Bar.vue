<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays, barOpen } = storeToRefs(mapStore)
import List from '@/components/List.vue'

const barHeight = computed(() => {
  if (!barOpen.value) {
    return '60px'
  }

  return '33.33%'
})
</script>

<template>
  <div id="bar" :style="`height:${barHeight}`">
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
  width: 100%;
  overflow: hidden;
  background: rgba(249, 249, 249, 0.5);
  transition: height 0.25s ease-in-out;
  .button {
    margin: 10px;
    padding: 10px;
    float: right;
    color: white;
    background: blue;
  }
}
</style>
