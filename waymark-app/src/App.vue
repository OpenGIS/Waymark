<script setup>
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useMapStore } from '@/stores/mapStore.js'

const mapStore = useMapStore()
let { visibleMarkers, leafletReady } = storeToRefs(mapStore)

import {
  IonHeader,
  IonFooter,
  IonIcon,
  IonToolbar,
  IonTitle,
  IonContent,
  IonMenu,
  IonButtons,
  IonMenuButton,
  IonPage
} from '@ionic/vue'

import { layersOutline } from 'ionicons/icons'

import Map from '@/components/Map.vue'
import Menu from '@/components/Menu.vue'

onMounted(() => {
  // setTimeout(() => {
  //   visibleMarkers = { data: ['joe'] }
  // }, 1000)
})
</script>

<template>
  <ion-app>
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
  </ion-app>
</template>
