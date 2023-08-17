<script setup>
import { computed, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { activeOverlay, detailOpenness } = storeToRefs(mapStore)

import Button from '@/components/Button.vue'
import Content from '@/components/Content.vue'
import Marker from '@/components/Marker.vue'

const detailHeight = computed(() => {
  //Closed
  if (detailOpenness.value == 0) {
    return '0px'
  }

  //Open
  if (detailOpenness.value == 1) {
    return '60px'
  }

  //Expanded
  return '16.67%'
})
</script>

<template>
  <div id="detail" v-show="detailOpenness" :style="`height:${detailHeight}`">
    <table>
      <tr class="item" v-if="activeOverlay.feature" @click="setActive">
        <!-- Image -->
        <td class="image">
          <Marker :typeData="activeOverlay.typeData" :featureType="activeOverlay.featureType" />
        </td>

        <!-- Title -->
        <td class="title">{{ activeOverlay.feature.properties.title }}</td>

        <!-- Expand -->
        <td class="action expand">
          <Button icon="ion-android-add" @click.stop="mapStore.changeDetailOpen(2)" />
        </td>

        <!-- Close -->
        <td class="action close">
          <Button icon="ion-close" @click.stop="mapStore.changeDetailOpen(0)" />
        </td>
      </tr>
    </table>

    <Content v-if="detailOpenness > 1"
      ><div v-if="activeOverlay">
        <!-- Image -->
        <img
          class="overlay-image"
          v-if="activeOverlay.feature.properties.image_large_url"
          :src="activeOverlay.feature.properties.image_large_url"
        />

        <!-- Description -->
        <div
          class="overlay-description"
          v-if="activeOverlay.feature.properties.description"
          v-html="activeOverlay.feature.properties.description"
        /></div
    ></Content>
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
}
</style>
