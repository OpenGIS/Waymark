<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { activeOverlay, detailOpen, detailExpanded } = storeToRefs(mapStore)

import Button from '@/components/Button.vue'

const detailHeight = computed(() => {
  if (!detailOpen.value) {
    return '0px'
  }

  if (!detailExpanded.value) {
    return '60px'
  }

  return '33.33%'
})
</script>

<template>
  <div id="detail" :style="`height:${detailHeight}`">
    <nav>
      <Button icon="ion-close" @click="mapStore.toggleDetail()"></Button>
    </nav>

    <div v-if="activeOverlay">
      <!-- Image -->
      <img
        class="overlay-image"
        v-if="activeOverlay.feature.properties.image_large_url"
        :src="activeOverlay.feature.properties.image_large_url"
      />
      <!-- Title -->
      <div class="overlay-title">{{ activeOverlay.feature.properties.title }}</div>

      <!-- Description -->
      <div
        class="overlay-description"
        v-if="activeOverlay.feature.properties.description"
        v-html="activeOverlay.feature.properties.description"
      />
    </div>
  </div>
</template>

<style lang="less">
#detail {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  overflow: hidden;
  overflow-y: scroll;
  background: rgba(249, 249, 249, 0.5);
  transition: all 0.1s;
}
</style>
