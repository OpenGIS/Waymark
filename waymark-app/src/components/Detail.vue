<script setup>
import { computed, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { activeOverlay, detailExpanded } = storeToRefs(mapStore)

import { expandedIcon } from '@/helpers/Common.js'

import Button from '@/components/Button.vue'
import Content from '@/components/Content.vue'
import Marker from '@/components/Marker.vue'

const detailHeight = computed(() => {
  //Closed
  if (!activeOverlay.value) {
    return '0px'
  }

  //Open
  if (!detailExpanded.value) {
    return '60px'
  }

  //Expanded
  return '16.67%'
})
</script>

<template>
  <div id="detail" v-if="activeOverlay.feature" :style="`height:${detailHeight}`">
    <table>
      <tr class="item" @click="setActive">
        <!-- Image -->
        <td class="image">
          <Marker :typeData="activeOverlay.typeData" :featureType="activeOverlay.featureType" />
        </td>

        <!-- Title -->
        <td class="title">{{ activeOverlay.feature.properties.title }}</td>

        <!-- Expand -->
        <td class="action expand">
          <Button
            :icon="expandedIcon(detailExpanded)"
            @click.stop="mapStore.toggleDetailExpanded()"
          />
        </td>

        <!-- Close -->
        <td class="action close">
          <Button icon="ion-close" @click.stop="activeOverlay = {}" />
        </td>
      </tr>
    </table>

    <Content v-show="detailExpanded">
      <!-- Image -->
      <img
        class="image"
        v-if="activeOverlay.feature.properties.image_large_url"
        :src="activeOverlay.feature.properties.image_large_url"
      />

      <!-- Description -->
      <div
        class="description"
        v-if="activeOverlay.feature.properties.description"
        v-html="activeOverlay.feature.properties.description"
      />
    </Content>
  </div>
</template>

<style lang="less">
#detail {
  position: absolute;
  top: 0;
  left: 0;
  width: 98%;
  padding: 1%;
  overflow: hidden;
  overflow-y: scroll;
  background: rgba(249, 249, 249, 0.9);
  transition: all 0.1s;
  box-shadow: 0 0 0 3px #eee;

  .title {
    font-size: 140%;
  }

  .description {
    padding-left: 45px;
  }
}
</style>
