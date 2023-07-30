<script setup>
import OverlayList from '@/components/OverlayList.vue'

const props = defineProps({
  overlaysByType: Object
})
</script>

<template>
  <div class="type-list">
    <div v-for="(byType, typeKey) in overlaysByType">
      <OverlayList :byType="byType" />
    </div>
  </div>
</template>

<style scoped lang="less">
.overlay-list {
  .list-header {
    padding: 15px;
    color: #fff;
    background: #333;
  }
  .list-content {
  }
}
</style>
