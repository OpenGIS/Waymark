<script setup>
import { ref } from 'vue'
import OverlayDetail from '@/components/OverlayDetail.vue'

const props = defineProps({
  byType: Object
})

let expanded = ref(true)
</script>

<template>
  <div class="overlay-list">
    <!-- Header -->
    <div class="list-header" @click="expanded = !expanded">
      <strong>{{ byType.title }} ({{ byType.overlays.length }})</strong>
    </div>

    <div v-show="expanded" class="list-content">
      <!-- List -->
      <OverlayDetail v-for="overlay in byType.overlays" :overlay="overlay" />
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
