<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { activeOverlay, detailOpen, detailExpanded } = storeToRefs(mapStore)

import Button from '@/components/Button.vue'
import Content from '@/components/Content.vue'

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
  <div v-show="detailOpen" id="detail" :style="`height:${detailHeight}`">
    <nav>
      <Button icon="ion-close" @click="mapStore.toggleDetail()"></Button>
    </nav>

    <Content
      ><div v-if="activeOverlay">
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
        /></div
    ></Content>
  </div>
</template>

<style lang="less">
#detail {
  position: absolute;
  top: 0;
  left: 0;
  width: 98%;
  padding: 1%;
  overflow: hidden;
  overflow-y: scroll;
  background: rgba(249, 249, 249, 0.7);
  transition: all 0.1s;
  box-shadow: 0 0 0 3px #eee;

  .button {
    position: absolute;
    top: 1%;
    right: 1%;
  }
}
</style>
