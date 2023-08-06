<script setup>
import { ref, computed, onMounted } from 'vue'
import TypeList from '@/components/TypeList.vue'
import { overlaysByType } from '@/helpers/Overlay.js'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'
import { IonModal } from '@ionic/vue'
import { close, funnel } from 'ionicons/icons'

const mapStore = useMapStore()
const { overlays, leafletMap, visibleMarkers } = storeToRefs(mapStore)

let modalOpen = ref(true)
let activeType = ref('marker')

const activeOverlays = computed(() => {
  return overlaysByType(
    visibleMarkers.value.filter((o) => {
      // if (o.featureType == 'marker') {
      //   if (!leafletMap.value.getBounds().contains(o.layer.getLatLng())) {
      //     return false
      //   }
      // }

      return o.featureType === activeType.value
    })
  )
})
</script>

<template>
  <ion-modal
    ref="modal"
    trigger="open-modal"
    :is-open="modalOpen"
    :initial-breakpoint="0.25"
    :breakpoints="[0, 0.25, 0.5, 0.75]"
  >
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-button>
            <ion-icon :icon="funnel"></ion-icon>
          </ion-button>
        </ion-buttons>

        <ion-buttons slot="end">
          <ion-button @click="modalOpen = false">
            <ion-icon :icon="close"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <!--   <nav>
    <div @click="activeType = 'marker'">Markers</div>
    <div @click="activeType = 'line'">Lines</div>
    <div @click="activeType = 'shape'">Shapes</div>
  </nav> -->

    <!-- Markers -->
    <TypeList :overlaysByType="activeOverlays" />
  </ion-modal>
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
