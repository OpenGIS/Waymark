<script setup>
import { IonCard, IonCardHeader, IonCardTitle, IonCardContent } from '@ionic/vue'

const props = defineProps({
  overlay: Object
})
</script>

<template>
  <ion-card>
    <!-- Image -->
    <img
      class="overlay-image"
      v-if="overlay.feature.properties.image_large_url"
      :src="overlay.feature.properties.image_large_url"
    />
    <ion-card-header>
      <!-- Title -->
      <ion-card-title>{{ overlay.feature.properties.title }}</ion-card-title>
    </ion-card-header>

    <ion-card-content>
      <!-- Description -->
      <div
        class="overlay-description"
        v-if="overlay.feature.properties.description"
        v-html="overlay.feature.properties.description"
      />
    </ion-card-content>
  </ion-card>
</template>
