<script setup>
import Map from '@/components/Map.vue'
import Bar from '@/components/Bar.vue'

import {
  IonHeader,
  IonFooter,
  IonIcon,
  IonToolbar,
  IonTitle,
  IonContent,
  IonMenu,
  IonButtons,
  IonMenuButton
} from '@ionic/vue'

import { layersOutline } from 'ionicons/icons'
</script>

<template>
  <!--   <ion-header>
    <ion-toolbar>
      <ion-title> Header </ion-title>
    </ion-toolbar>
  </ion-header> -->

  <ion-menu side="end" contentId="main-content">
    <ion-header>
      <ion-toolbar>
        <ion-title>Menu Content</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <Bar />
    </ion-content>
  </ion-menu>

  <div class="ion-page" id="main-content">
    <ion-header>
      <ion-toolbar>
        <ion-title>Menu</ion-title>
        <ion-buttons slot="end">
          <ion-menu-button>
            <ion-icon :icon="layersOutline"></ion-icon>
          </ion-menu-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content>
      <Map />
    </ion-content>
  </div>

  <!--   <ion-footer>
    <ion-toolbar>
      <ion-title> Footer </ion-title>
    </ion-toolbar>
  </ion-footer> -->
</template>

<!-- <template>
  <main>
    <Map />

    <Bar />
  </main>
</template>
 -->
<style scoped>
main {
  /*  display: flex;
  width: 100%;
  height: 100%;
*/
}
</style>
