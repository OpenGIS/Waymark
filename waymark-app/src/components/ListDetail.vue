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
  <ion-item @click="setActive" class="ion-justify-content-start">
    <ion-thumbnail slot="start">
      <img
        v-if="feature_props.image_thumbnail_url"
        :alt="feature_props.title"
        :src="feature_props.image_thumbnail_url"
      />
    </ion-thumbnail>

    <ion-label
      ><h2>{{ feature_props.title }}</h2></ion-label
    >
    <div class="display-toggle">
      <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
    </div>
  </ion-item>
</template>
