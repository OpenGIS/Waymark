<script setup>
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleMarkers, leafletReady } = storeToRefs(mapStore)

import {
  IonButtons,
  IonButton,
  IonHeader,
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
  <ion-header>
    <ion-toolbar>
      <!-- <ion-title>App</ion-title> -->
      <ion-buttons slot="end">
        <ion-button id="open-modal" expand="block"
          >{{ visibleMarkers.length }} <ion-icon :icon="layersOutline"></ion-icon
        ></ion-button>
      </ion-buttons>
    </ion-toolbar>
  </ion-header>
  <ion-content>
    <Modal />
    <Map />
  </ion-content>
</template>
