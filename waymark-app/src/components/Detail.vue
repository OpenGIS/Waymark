<script setup>
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { activeOverlay } = storeToRefs(mapStore)
</script>

<template>
  <div id="detail">
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
