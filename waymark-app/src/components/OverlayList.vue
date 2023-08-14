<script setup>
import { ref } from 'vue'
import ListItem from '@/components/ListItem.vue'
import Marker from '@/components/Marker.vue'

const props = defineProps({
  byType: Object
})

let expanded = ref(true)
let visible = ref(true)

const toggleHighlight = (overlay) => {
  return
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

const overlayStyle = () => {
  switch (props.byType.featureType) {
    case 'marker':
      return `color:${props.byType.typeData.icon_colour};background-color:${props.byType.typeData.marker_colour}`

      break

    case 'line':
      console.log(props.byType.typeData)
      return `color:#fff;background-color:${props.byType.typeData.line_colour}`

      break
  }
}
</script>

<template>
  <div class="type">
    <!-- Heading -->
    <div class="type-heading" @click="expanded = !expanded" :style="overlayStyle()">
      <Marker :typeData="byType.typeData" :featureType="byType.featureType" />

      <div class="type-title">{{ byType.title }}</div>

      <div class="display-toggle">
        <input type="checkbox" checked="visible" @click.stop="toggleVisible()" />
      </div>
    </div>

    <!-- List -->
    <ListItem
      v-show="expanded"
      v-for="(overlay, typeKey, index) in byType.overlays"
      :overlay="overlay"
      @mouseenter="toggleHighlight(overlay)"
      @mouseleave="toggleHighlight(overlay)"
      :key="`${byType.featureType}-${typeKey}-${index}`"
    />
  </div>
</template>

<style lang="less">
.type-heading {
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
