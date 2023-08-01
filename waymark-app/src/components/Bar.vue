<script setup>
import { ref, computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import TypeList from '@/components/TypeList.vue'
import { useMapStore } from '@/stores/mapStore.js'
import { overlaysByType } from '@/helpers/Overlay.js'

const mapStore = useMapStore()
const { overlays } = storeToRefs(mapStore)

let activeType = ref('marker')

const activeOverlays = computed(() => {
  return overlaysByType(
    overlays.value.filter((o) => {
      return o.featureType === activeType.value
    })
  )
})
</script>

<template>
  <div id="bar">
    <nav>
      <div @click="activeType = 'marker'">Markers</div>
      <div @click="activeType = 'line'">Lines</div>
      <div @click="activeType = 'shape'">Shapes</div>
    </nav>

    <div class="type-lists">
      <!-- Markers -->
      <TypeList :overlaysByType="activeOverlays" />
    </div>
  </div>
</template>

<style lang="less">
#bar {
  nav {
    display: flex;
    div {
      width: 50px;
      height: 50px;
      background: blue;
    }
  }
}
</style>
