<script setup>
import { ref } from 'vue'

import { visibleIcon, expandedIcon } from '@/helpers/Common.js'

import ListRow from '@/components/ListRow.vue'
import Marker from '@/components/Marker.vue'
import Button from '@/components/Button.vue'

const props = defineProps({
  byType: Object
})

let expanded = ref(false)
let visible = ref(true)

const toggleHighlight = (overlay) => {
  return
  const element = overlay.layer.getElement()

  element.classList.toggle('overlay-highlight')
}

const toggleExpanded = () => {
  expanded.value = !expanded.value
}

const toggleVisible = () => {
  visible.value = !visible.value

  //Close Type if hiding all
  if (!visible.value) {
    expanded.value = false
  }

  const overlays = props.byType.overlays

  for (let i in overlays) {
    const element = overlays[i].element

    if (!visible.value) {
      element.classList.add('overlay-hidden')
    } else {
      element.classList.remove('overlay-hidden')
    }

    // expanded.value = !element.classList.contains('overlay-hidden')
  }
}

const overlayStyle = () => {
  switch (props.byType.featureType) {
    case 'marker':
      return `color:${props.byType.typeData.icon_colour};border-color:${props.byType.typeData.marker_colour}`

      break

    case 'line':
      return `color:#fff;background-color:${props.byType.typeData.line_colour}`

      break
  }
}
</script>

<template>
  <div class="type">
    <table>
      <!-- Heading -->
      <tr class="heading" :style="overlayStyle()" @click.stop="toggleExpanded()">
        <!-- Image -->
        <td class="image">
          <Marker :typeData="byType.typeData" :featureType="byType.featureType" />
        </td>

        <!-- Title -->
        <td class="title">
          {{ byType.title }} <span class="count">{{ byType.overlays.length }}</span>
        </td>

        <!-- Expand -->
        <td class="action expand">
          <Button :icon="expandedIcon(expanded)"></Button>
        </td>

        <!-- Visible -->
        <td class="action visible">
          <Button :icon="visibleIcon(visible)" @click.stop="toggleVisible()"></Button>
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
.type {
  table {
    margin-bottom: 0;
    tr.heading {
      border-bottom-width: 2px;
      border-bottom-style: solid;

      td {
        background: rgba(255, 255, 255, 0.7);

        &.image {
          position: relative;
          .waymark-marker {
            display: flex;
            flex-direction: row;
            .waymark-marker-icon::before {
              padding-top: 0 !important;
              font-size: 24px !important;
            }
            // .waymark-marker-background {
            //   display: none !important;
            // }
          }

          .count {
            position: absolute;
            top: 15px;
            left: 5px;
          }
        }

        &.title {
          font-size: 130%;
          color: #000;
          text-shadow: 1px 1px 1px #fff;

          .count {
            float: right;
            opacity: 0.5;
            font-size: 80%;
            &::before {
              content: 'x';
            }
          }
        }

        &.action {
          &.expand {
          }
        }
      }
    }
  }
}
</style>
