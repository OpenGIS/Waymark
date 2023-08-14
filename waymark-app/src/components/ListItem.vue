<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
const mapStore = useMapStore()

import { IonItem, IonLabel, IonThumbnail } from '@ionic/vue'

const props = defineProps({
  overlay: Object
})

const feature_props = props.overlay.feature.properties

let visible = ref(true)

const toggleVisible = () => {
  visible.value = !visible.value

  const element = props.overlay.layer.getElement()

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
  <div class="list-item" @click="setActive">
    <div class="list-image">
      <img
        v-if="feature_props.image_thumbnail_url"
        :alt="feature_props.title"
        :src="feature_props.image_thumbnail_url"
      />
    </div>

    <div class="list-title">{{ feature_props.title }}</div>

    <div class="display-toggle">
      <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
    </div>
  </div>
</template>
