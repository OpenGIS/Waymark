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
  console.log(props.overlay)
  const element = props.overlay.element

  if (!visible.value) {
    element.classList.add('overlay-hidden')
  } else {
    element.classList.remove('overlay-hidden')
  }
}

const setActive = () => {
  mapStore.setActiveOverlay(props.overlay)
}
</script>

<template>
  <tr class="item" @click="setActive">
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
    <td class="go">
      <Button icon="ion-android-search" />
    </td>

    <!-- Visible -->
    <td class="visible">
      <Button :icon="visibleIcon(visible)" @click.stop="toggleVisible()" />
    </td>
  </tr>
</template>

<style lang="less">
.item {
  height: 60px;
  &:nth-of-type(odd) {
    background-color: #eee;
  }

  .image {
    width: 60px;
    img {
      max-width: 100%;
    }
  }

  .title {
    font-weight: bold;
  }

  .go,
  .visible {
    padding: 0;
    width: 60px;
  }
}
</style>
