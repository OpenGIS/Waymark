<script setup>
import { ref } from 'vue'
import ListDetail from '@/components/ListDetail.vue'
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

  <!-- List -->
  <ListDetail
    v-show="expanded"
    v-for="overlay in byType.overlays"
    :overlay="overlay"
    @mouseenter="toggleHighlight(overlay)"
    @mouseleave="toggleHighlight(overlay)"
  />
</template>

<style lang="less">
ion-accordion-group {
  margin: 0;
}
ion-list-header {
  ion-label {
    padding-left: 10px;
    font-weight: bold;
    font-size: 120%;
  }

  .waymark-marker {
    display: flex;
    flex-direction: row;
    .waymark-marker-icon::before {
      padding-top: 0 !important;
      font-size: 24px !important;
    }
    .waymark-marker-background {
      display: none !important;
    }
  }
}
</style>
