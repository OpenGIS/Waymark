<script setup>
import OverlayDetail from '@/components/OverlayDetail.vue'

const props = defineProps({
  overlaysByType: Object
})
</script>

<template>
  <div class="overlay-list">
    <div v-for="(byType, typeKey) in overlaysByType" :byType="byType">
      <!-- Header -->
      <strong>{{ byType.title }}</strong>

      <!-- List -->
      <OverlayDetail v-for="overlay in byType.overlays" :overlay="overlay" />
    </div>
  </div>
</template>

<style scoped lang="less"></style>
