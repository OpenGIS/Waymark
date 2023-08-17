<script setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()

import { visibleIcon } from '@/helpers/Common.js'
import Button from '@/components/Button.vue'

const props = defineProps({
  overlay: Object
})

const feature_props = props.overlay.feature.properties

let visible = ref(true)

const toggleVisible = () => {
  visible.value = !visible.value

  const element = props.overlay.element

  if (!visible.value) {
    element.classList.add('overlay-hidden')
  } else {
    element.classList.remove('overlay-hidden')
  }
}

const centerOn = () => {
  mapStore.setFocus(props.overlay.feature.geometry.coordinates)
}

const setActive = () => {
  mapStore.setActiveOverlay(props.overlay)
}

const toggleHover = () => {
  mapStore.toggleHoverOverlay(props.overlay)
}
</script>

<template>
  <tr class="item" @click="setActive" @mouseenter="toggleHover" @mouseleave="toggleHover">
    <!-- Image -->
    <td class="image">
      <img
        v-if="feature_props.image_thumbnail_url"
        :alt="feature_props.title"
        :src="feature_props.image_thumbnail_url"
      />
    </td>

    <!-- Title -->
    <td class="title">{{ feature_props.title }}</td>

    <!-- Go To -->
    <td class="action go">
      <Button icon="ion-android-search" @click.stop="centerOn()" />
    </td>

    <!-- Visible -->
    <td class="action visible">
      <Button :icon="visibleIcon(visible)" @click.stop="toggleVisible()" />
    </td>
  </tr>
</template>

<style lang="less">
.item {
  height: 60px;
  &:nth-of-type(odd) {
    background: rgba(255, 255, 255, 0.7);
  }
}
</style>
