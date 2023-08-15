<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays, barOpen } = storeToRefs(mapStore)
import List from '@/components/List.vue'
import Button from '@/components/Button.vue'

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
      <!-- fa-compass -->
      <!-- fa-crosshairs -->
      <!-- fa-leanpub -->
      <!-- fa-line-chart -->
      <!-- fa-location-arrow -->
      <!-- fa-map-o -->

      <!-- Layers -->
      <Button icon="fa-navicon" @click="mapStore.toggleBar()">
        <!-- {{ visibleOverlays.length }} -->
      </Button>
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
  max-height: 100%;
  overflow: auto;
  background: rgba(249, 249, 249, 0.5);
  transition: height 0.1s jump-start;
}
</style>
