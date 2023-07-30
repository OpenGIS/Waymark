<script setup>
import { ref } from 'vue'
import OverlayDetail from '@/components/OverlayDetail.vue'

const props = defineProps({
  byType: Object
})

let expanded = ref(true)
let visible = ref(true)

const toggleHighlight = (overlay) => {
  const element = overlay.layer.getElement()

  element.classList.toggle('overlay-highlight')
}

const toggleVisible = (overlays) => {
  for (let i in overlays) {
    const element = overlays[i].layer.getElement()

    element.classList.toggle('overlay-hidden')
  }
}
</script>

<template>
  <div class="overlay-list">
    <!-- Header -->
    <div class="list-header" @click="expanded = !expanded">
      <strong>{{ byType.title }} ({{ byType.overlays.length }})</strong>

      <div class="display-toggle">
        <input type="checkbox" checked="visible" @change="toggleVisible(byType.overlays)" />
      </div>
    </div>

    <div v-show="expanded" class="list-content">
      <!-- List -->
      <OverlayDetail
        v-for="overlay in byType.overlays"
        :overlay="overlay"
        @mouseenter="toggleHighlight(overlay)"
        @mouseleave="toggleHighlight(overlay)"
      />
    </div>
  </div>
</template>

<style lang="less">
.overlay-list {
  .list-header {
    padding: 15px;
    color: #fff;
    background: #333;

    .display-toggle {
      float: right;
      color: red;
    }
  }
  .list-content {
  }
}
</style>
