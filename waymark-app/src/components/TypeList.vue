<script setup>
import OverlayList from '@/components/OverlayList.vue'

defineProps({
  overlaysByType: Object
})
</script>

<template>
  <div
    class="type-list"
    v-for="(byType, typeKey, index) in overlaysByType"
    :key="`${byType.featureType}-${typeKey}-${index}`"
  >
    <OverlayList :byType="byType" />
  </div>
</template>
