<script setup>
import { getTypeData } from '@/helpers/Overlay.js'
defineProps({
  overlay: Object
})
</script>

<template>
  <article>
    <header>
      <div
        v-if="overlay.featureType == 'marker'"
        class="waymark-marker"
        v-html="overlay.typeData.iconData.html"
      />

      <h1>{{ overlay.feature.properties.title }}</h1>
    </header>
    <main
      v-if="overlay.feature.properties.description || overlay.feature.properties.image_large_url"
    >
      <p v-if="overlay.feature.properties.description">
        {{ overlay.feature.properties.description }}
      </p>

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
</style>
