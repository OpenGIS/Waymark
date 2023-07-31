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

const toggleVisible = () => {
  visible.value = !visible.value
  expanded.value = visible

  const overlays = props.byType.overlays

  for (let i in overlays) {
    const element = overlays[i].layer.getElement()

    if (!visible.value) {
      element.classList.add('overlay-hidden')
    } else {
      element.classList.remove('overlay-hidden')
    }

    expanded.value = !element.classList.contains('overlay-hidden')
  }
}
</script>

<template>
  <div class="overlay-list">
    <!-- Header -->
    <div class="list-header" @click="expanded = !expanded">
      <!-- Icon -->
      <div class="overlay-icon">
        <div
          v-if="byType.featureType == 'marker'"
          :class="byType.typeData.iconData.className"
          v-html="byType.typeData.iconData.html"
          :style="`width:${byType.typeData.iconData.iconSize[0]}px;height:${byType.typeData.iconData.iconSize[1]}px`"
        />
      </div>

      <strong>{{ byType.title }} ({{ byType.overlays.length }})</strong>

      <div class="display-toggle">
        <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
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
    position: relative;
    padding: 15px;
    padding-left: 45px;
    color: #fff;
    background: #333;

    .waymark-marker {
      position: absolute;
      top: 6px;
      left: 10px;
    }

    .display-toggle {
      float: right;
      color: red;
    }
  }
  .list-content {
  }
}
</style>
