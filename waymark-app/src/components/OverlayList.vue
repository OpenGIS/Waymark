<script setup>
import { ref } from 'vue'
import OverlayDetail from '@/components/OverlayDetail.vue'
import Marker from '@/components/Marker.vue'

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
  <ion-list-header
    v-if="byType.featureType == 'marker'"
    @click="expanded = !expanded"
    :style="`color:${byType.typeData.icon_colour};background-color:${byType.typeData.marker_colour}`"
  >
    <Marker :typeData="byType.typeData" :featureType="byType.featureType" />

    <ion-label>{{ byType.title }}</ion-label>

    <div class="display-toggle">
      <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
    </div>
  </ion-list-header>

  <ion-item>
    <ion-accordion-group multiple="true">
      <!-- List -->
      <OverlayDetail
        v-show="expanded"
        v-for="overlay in byType.overlays"
        :overlay="overlay"
        @mouseenter="toggleHighlight(overlay)"
        @mouseleave="toggleHighlight(overlay)"
      />
    </ion-accordion-group>
  </ion-item>
</template>

<style lang="less">
ion-accordion-group {
  margin: 0;
}
ion-list-header {
  .waymark-marker {
    position: absolute;
    bottom: 6px;
    right: 10px;

    .waymark-marker-background {
      display: none !important;
    }
  }
}
</style>
