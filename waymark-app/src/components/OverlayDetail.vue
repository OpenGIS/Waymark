<script setup>
import { ref } from 'vue'
import { getTypeData } from '@/helpers/Overlay.js'

const props = defineProps({
  overlay: Object
})

const feature_props = props.overlay.feature.properties

let expanded = ref(false)
let visible = ref(true)
let hasBody = feature_props.description || feature_props.image_large_url

const toggleVisible = (overlay) => {
  const element = overlay.layer.getElement()

  element.classList.toggle('overlay-hidden')
}
</script>

<template>
  <article class="overlay-detail" :class="hasBody ? 'overlay-has-body' : ''">
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
        {{ feature_props.title }}
      </div>

      <div v-if="hasBody" class="expand-icon">
        <div v-if="expanded">[-]</div>
        <div v-else>[+]</div>
      </div>

      <div class="display-toggle">
        <input type="checkbox" checked="visible" @change="toggleVisible(overlay)" />
      </div>
    </header>

    <!-- Body -->
    <main v-show="expanded && hasBody">
      <!-- Description -->
      <div
        class="overlay-description"
        v-if="feature_props.description"
        v-html="feature_props.description"
      />

      <!-- Image -->
      <img
        class="overlay-image"
        v-if="feature_props.image_large_url"
        :src="feature_props.image_large_url"
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

  &.overlay-has-body {
    header {
      cursor: pointer;
    }
  }

  header {
    display: flex;
    padding: 7px;
    background: #999;

    .expand-icon {
      float: right;
    }

    .overlay-icon {
      display: flex;

      .waymark-marker {
        position: relative;
        width: 25px;
        height: 25px;
      }
    }
    .overlay-title {
      width: 100%;
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
