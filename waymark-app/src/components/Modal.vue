<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import TypeList from '@/components/TypeList.vue'
import OverlayDetail from '@/components/OverlayDetail.vue'
import { overlaysByType } from '@/helpers/Overlay.js'
import {
  IonModal,
  IonContent,
  IonHeader,
  IonButtons,
  IonButton,
  IonIcon,
  IonToolbar,
  IonSegmentButton,
  IonSegment
} from '@ionic/vue'
import { close, funnel, locationOutline, analyticsOutline, shapesOutline } from 'ionicons/icons'

import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
const { overlays, leafletMap, visibleOverlays, activeOverlay } = storeToRefs(mapStore)

const modal = ref()
mapStore.setModal(modal)
const modalOpen = ref(true)
const modalBreakpoint = ref(0.33)

const activeType = ref('marker')

const activeOverlays = computed(() => {
  return overlaysByType(
    visibleOverlays.value.filter((o) => {
      // if (o.featureType == 'marker') {
      //   if (!leafletMap.value.getBounds().contains(o.layer.getLatLng())) {
      //     return false
      //   }
      // }

      return o.featureType === activeType.value
    })
  )
})

const modalChange = (e) => {
  switch (e.type) {
    case 'did-dismiss':
      modalOpen.value = false
      modalBreakpoint.value = 0

      mapStore.setMapHeight(100)

      break
    case 'ion-breakpoint-did-change':
    case 'did-present':
    default:
      modalOpen.value = true

      e.target.getCurrentBreakpoint().then((breakpoint) => {
        modalBreakpoint.value = breakpoint

        mapStore.setMapHeight(100 - breakpoint * 100)
      })
      break
  }
}

onMounted(() => {
  // console.log(modalBreakpoint.value)
})

watch(modalBreakpoint, () => {
  console.log(modalBreakpoint.value)
})
</script>

<template>
  <ion-modal
    ref="modal"
    trigger="open-modal"
    :is-open="modalOpen"
    backdrop-breakpoint="1"
    :initial-breakpoint="0.33"
    handleBehavior="cycle"
    @didPresent="modalChange"
    @didDismiss="modalChange"
    @ionBreakpointDidChange="modalChange"
    :breakpoints="[0, 0.33, 0.66]"
    keep-contents-mounted="true"
  >
    <ion-header mode="ios" translucent="true">
      <ion-toolbar>
        <!--         <ion-buttons slot="start">
          <ion-button>
            <ion-icon :icon="funnel"></ion-icon>
          </ion-button>
        </ion-buttons> -->

        <ion-buttons slot="end">
          <ion-button @click="modalOpen = false">
            <ion-icon :icon="close"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <OverlayDetail v-if="activeOverlay && modalBreakpoint > 0.33" :overlay="activeOverlay" />

    <ion-content>
      <!-- Type Nav -->
      <ion-segment :value="activeType">
        <ion-segment-button @click="activeType = 'marker'" value="marker">
          <ion-icon :icon="locationOutline"></ion-icon>
        </ion-segment-button>
        <ion-segment-button @click="activeType = 'line'" value="line">
          <ion-icon :icon="analyticsOutline"></ion-icon>
        </ion-segment-button>
        <ion-segment-button @click="activeType = 'shape'" value="shape">
          <ion-icon :icon="shapesOutline"></ion-icon>
        </ion-segment-button>
      </ion-segment>

      <!-- Overlays (by Type) -->
      <TypeList :overlaysByType="activeOverlays" />
    </ion-content>
  </ion-modal>
</template>

<style>
ion-toolbar {
  --background: transparent;
}
</style>
