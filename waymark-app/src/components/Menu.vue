<script setup>
import MenuContent from '@/components/MenuContent.vue'
</script>
<template>
  <ion-menu side="end" contentId="main">
    <ion-header>
      <ion-toolbar>
        <ion-title>Menu Content</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <MenuContent />
    </ion-content>
  </ion-menu>
</template>
