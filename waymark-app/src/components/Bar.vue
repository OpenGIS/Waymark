<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays, barOpen } = storeToRefs(mapStore)
import List from '@/components/List.vue'
import Button from '@/components/Button.vue'
import Content from '@/components/Content.vue'

const barHeight = computed(() => {
  if (!barOpen.value) {
    return '0'
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
    <Content v-show="barOpen">
      <!-- List -->
      <List />
    </Content>
  </div>
</template>

<style lang="less">
#bar {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 98%;
  padding: 1%;
  padding-top: 60px;
  max-height: 100%;
  overflow: auto;
  background: rgba(249, 249, 249, 0.9);
  transition: height 0.1s jump-start;
  nav {
    .button {
      position: absolute;
      top: 1%;
      right: 1%;
    }
  }
}
</style>
