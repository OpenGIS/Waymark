<script setup>
import OverlayDetail from '@/components/OverlayDetail.vue'

const props = defineProps({
  overlaysByType: Object
})
</script>

<template>
  <div v-for="(overlays, typeKey) in overlaysByType" :overlays="overlays">
    <strong>{{ typeKey }}</strong>
    <OverlayDetail v-for="overlay in overlays" :overlay="overlay" />
  </div>
</template>

<style scoped lang="less"></style>
