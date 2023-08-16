<script setup>
import { ref } from 'vue'

import { visibleIcon } from '@/helpers/Common.js'

import ListRow from '@/components/ListRow.vue'
import Marker from '@/components/Marker.vue'
import Button from '@/components/Button.vue'

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
    const element = overlays[i].element

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
    <table>
      <!-- Heading -->
      <tr class="heading" @click="expanded = !expanded" :style="overlayStyle()">
        <!-- Image -->
        <td class="image">
          <Marker :typeData="byType.typeData" :featureType="byType.featureType" />
        </td>

        <!-- Title -->
        <td class="title">{{ byType.title }}</td>

        <!-- Go To -->
        <td class="go"></td>

        <!-- Visible -->
        <td class="visible">
          <Button :icon="visibleIcon(visible)" @click.stop="toggleVisible()" />
        </td>
      </tr>

      <!-- List -->
      <ListRow
        class="content"
        v-show="expanded"
        v-for="(overlay, typeKey, index) in byType.overlays"
        :overlay="overlay"
        @mouseenter="toggleHighlight(overlay)"
        @mouseleave="toggleHighlight(overlay)"
        :key="`${byType.featureType}-${typeKey}-${index}`"
      />
    </table>
  </div>
</template>

<style lang="less">
.heading {
  .image {
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

  .title {
    color: #fff;
    text-shadow: 1px 1px 1px black;
  }
}

td {
  padding: 1%;
  vertical-align: middle;

  &.image {
    width: 60px;

    img {
      max-width: 50px;
    }
  }

  &.title {
    font-weight: bold;
  }

  &.go,
  &.visible {
    padding: 0;
    width: 60px;
  }
}
</style>
