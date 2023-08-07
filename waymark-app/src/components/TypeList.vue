<script setup>
import OverlayList from '@/components/OverlayList.vue'
import { IonList } from '@ionic/vue'

const props = defineProps({
  overlaysByType: Object
})
</script>

<template>
  <ion-list v-for="(byType, typeKey) in overlaysByType">
    <OverlayList :byType="byType" />
  </ion-list>
</template>

<style scoped lang="less"></style>
