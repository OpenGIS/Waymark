<script setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { visibleOverlays, barOpen } = storeToRefs(mapStore)
import List from '@/components/List.vue'
import Button from '@/components/Button.vue'
import Content from '@/components/Content.vue'

const barHeight = computed(() => {
  if (!barOpen.value) {
    return '0'
  }

  return '33.33%'
})
</script>

<template>
  <div id="bar" :style="`height:${barHeight}`">
    <!-- Nav -->
    <nav id="bar-nav" :style="`bottom:${barHeight}`">
      <!-- fa-compass -->
      <!-- fa-crosshairs -->
      <!-- fa-leanpub -->
      <!-- fa-line-chart -->

      <!-- fa-location-arrow -->
      <!-- Layers -->
      <div class="nav-item">
        <Button :active="barOpen" icon="ion-social-buffer" @click="mapStore.toggleBar()" />
      </div>
      <!-- fa-map-o -->

      <!-- Layers -->
      <div class="nav-item"></div>

      <!-- Layers -->
      <div class="nav-item"></div>
      <!-- fa-map-o -->

      <!-- Layers -->
      <div class="nav-item">
        <Button icon="fa-location-arrow" @click="mapStore.toggleBar()" />
      </div>
    </nav>

    <!-- Content -->
    <Content v-show="barOpen">
      <!-- List -->
      <List />
    </Content>
  </div>
</template>

<style lang="less">
#bar {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 98%;
  padding: 0 1%;
  // padding-top: 60px;
  max-height: 100%;
  overflow: auto;
  background: rgba(249, 249, 249, 0.9);
  transition: height 0.1s jump-start;
  #bar-nav {
    position: fixed;
    left: 0;
    width: 100%;
    height: 65px;
    display: flex;
    background: rgba(249, 249, 249, 0.9);
    border-bottom: 2px solid #eee;
    .nav-item {
      width: 25%;
      // position: absolute;
      // top: 1%;
      // right: 1%;
      &:first-child {
        .button {
          margin-left: 14px;
        }
      }
      &:last-child {
        .button {
          margin-right: 14px;
        }
      }
      &.active {
        background: #333;
      }
    }
  }
}
</style>
