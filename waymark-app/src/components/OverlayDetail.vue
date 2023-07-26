<script setup>
import { getTypeData } from '@/helpers/Overlay.js'
defineProps({
  overlay: Object
})
</script>

<template>
  <article class="overlay-detail">
    <!-- Header -->
    <header>
      <!-- Icon -->
      <div class="overlay-icon">
        <div
          v-if="overlay.featureType == 'marker'"
          class="waymark-marker"
          v-html="overlay.typeData.iconData.html"
        />
      </div>

      <!-- Title -->
      <div class="overlay-title">
        {{ overlay.feature.properties.title }}
      </div>
    </header>

    <!-- Body -->
    <main
      v-if="overlay.feature.properties.description || overlay.feature.properties.image_large_url"
    >
      <!-- Description -->
      <div
        v-if="overlay.feature.properties.description"
        v-html="overlay.feature.properties.description"
      />

      <!-- Image -->
      <img
        v-if="overlay.feature.properties.image_large_url"
        :src="overlay.feature.properties.image_large_url"
      />
    </main>
  </article>
</template>

<style scoped lang="less">
img {
  max-width: 100%;
}

.overlay-detail {
  border: 1px solid blue;
  header {
    border: 1px solid pink;
    .overlay-icon {
      border: 1px solid red;
    }
  }
}
</style>
