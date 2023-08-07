<script setup>
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleMarkers, leafletReady } = storeToRefs(mapStore)

import {
  IonPage,
  IonButtons,
  IonButton,
  IonFooter,
  IonContent,
  IonToolbar,
  IonTitle,
  IonIcon
} from '@ionic/vue'

import { layersOutline } from 'ionicons/icons'

import Map from '@/components/Map.vue'
import Modal from '@/components/Modal.vue'
</script>

<template>
  <ion-page>
    <ion-content fullscreen="true">
      <Modal />
      <Map />
    </ion-content>

    <ion-footer mode="ios" translucent="true">
      <ion-toolbar>
        <!-- <ion-title>App</ion-title> -->
        <ion-buttons slot="end">
          <ion-button id="open-modal" expand="block"
            >{{ visibleMarkers.length }} <ion-icon :icon="layersOutline"></ion-icon
          ></ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-footer>
  </ion-page>
</template>

<style>
ion-footer {
  position: absolute;
  bottom: 0;
  /*  background: rgba(255, 255, 255, 0.7);*/
}
ion-toolbar {
  --background: transparent;
}
</style>
