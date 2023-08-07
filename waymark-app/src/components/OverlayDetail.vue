<script setup>
import { ref, onMounted } from 'vue'

import { IonCard, IonCardHeader, IonCardTitle, IonCardContent } from '@ionic/vue'

const props = defineProps({
  overlay: Object
})

onMounted(() => {
  console.log(props.overlay)
})

let feature_props = props.overlay.feature.properties

let hasBody = feature_props.description || feature_props.image_large_url
</script>

<template>
  <ion-card>
    <ion-card-header>
      <ion-card-title>{{ feature_props.title }}</ion-card-title>
    </ion-card-header>

    <ion-card-content>
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
    </ion-card-content>
  </ion-card>
</template>
