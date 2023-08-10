<script setup>
import OverlayList from '@/components/OverlayList.vue'
import { IonList } from '@ionic/vue'

const props = defineProps({
  overlaysByType: Object
})
</script>

<template>
  <ion-list
    v-for="(byType, typeKey, index) in overlaysByType"
    :key="`${byType.featureType}-${typeKey}-${index}`"
  >
    <OverlayList :byType="byType" />
  </ion-list>
</template>
