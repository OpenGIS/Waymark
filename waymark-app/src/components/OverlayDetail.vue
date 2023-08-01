<script setup>
import { ref } from 'vue'

const props = defineProps({
  overlay: Object
})

const feature_props = props.overlay.feature.properties

let visible = ref(true)
let hasBody = feature_props.description || feature_props.image_large_url

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
  <ion-accordion :value="overlay.id">
    <ion-item slot="header" color="light">
      <ion-label>{{ feature_props.title }}</ion-label>
      <div class="display-toggle">
        <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
      </div>
    </ion-item>
    <div v-if="hasBody" slot="content">
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
    </div>
  </ion-accordion>
</template>

<style scoped lang="less">
img {
  max-width: 100%;
}
</style>
