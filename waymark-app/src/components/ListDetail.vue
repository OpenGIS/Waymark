<script setup>
import { ref } from 'vue'

import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()

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
</script>

<template>
  <ion-item @click="mapStore.setActiveOverlay(props.overlay)">
    <ion-label
      ><h2>{{ feature_props.title }}</h2></ion-label
    >
    <div class="display-toggle">
      <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
    </div>
  </ion-item>
</template>
