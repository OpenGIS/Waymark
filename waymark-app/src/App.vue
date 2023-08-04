<script setup>
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleMarkers, leafletReady } = storeToRefs(mapStore)

import {
  IonButton,
  IonModal,
  IonHeader,
  IonContent,
  IonToolbar,
  IonTitle,
  IonItem,
  IonList,
  IonAvatar,
  IonImg,
  IonLabel,
  IonSearchbar
} from '@ionic/vue'

import { layersOutline } from 'ionicons/icons'

import Map from '@/components/Map.vue'
import MenuContent from '@/components/MenuContent.vue'

onMounted(() => {
  // setTimeout(() => {
  //   visibleMarkers = { data: ['joe'] }
  // }, 1000)
})
</script>

<template>
  <ion-header>
    <ion-toolbar>
      <ion-title>App</ion-title>
      <ion-buttons slot="end">
        <ion-button id="open-modal" expand="block"
          >{{ visibleMarkers.length }} <ion-icon :icon="layersOutline"></ion-icon
        ></ion-button>
      </ion-buttons>
    </ion-toolbar>
  </ion-header>
  <ion-content>
    <ion-content>
      <Map />

      <ion-modal
        ref="modal"
        trigger="open-modal"
        :initial-breakpoint="0.25"
        :breakpoints="[0, 0.25, 0.5, 0.75]"
      >
        <MenuContent />
      </ion-modal>
    </ion-content>
  </ion-content>
</template>

<!--   <ion-app>
    <Menu />

    <div class="ion-page" id="main">
      <ion-header>
        <ion-toolbar>
          <ion-title>Menu</ion-title>
          <ion-buttons slot="end">
            <ion-menu-button>
              {{ visibleMarkers.length }}
              <ion-icon :icon="layersOutline"></ion-icon>
            </ion-menu-button>
          </ion-buttons>
        </ion-toolbar>
      </ion-header>

      <ion-content>
        <Map />
      </ion-content>
    </div>
  </ion-app> -->
