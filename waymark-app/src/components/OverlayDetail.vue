<script setup>
import { ref } from 'vue'
import { getTypeData } from '@/helpers/Overlay.js'

defineProps({
  overlay: Object
})

let expanded = ref(false)
</script>

<template>
  <article class="overlay-detail">
    <!-- Header -->
    <header @click="expanded = !expanded">
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
      v-show="
        expanded &&
        (overlay.feature.properties.description || overlay.feature.properties.image_large_url)
      "
    >
      <!-- Description -->
      <div
        class="overlay-description"
        v-if="overlay.feature.properties.description"
        v-html="overlay.feature.properties.description"
      />

      <!-- Image -->
      <img
        class="overlay-image"
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
  border-bottom: 1px solid #333;

  header {
    display: flex;
    padding: 7px;
    cursor: pointer;
    background: #999;

    .overlay-icon {
      display: flex;

      .waymark-marker {
        position: relative;
        width: 25px;
        height: 25px;
      }
    }
    .overlay-title {
      padding: 7px;
      font-weight: bold;
    }
  }

  main {
    padding: 15px;

    .overlay-description {
      margin-bottom: 15px;
    }
  }
}
</style>
