<script setup>
import Map from '@/components/Map.vue'
import Bar from '@/components/Bar.vue'
</script>

<template>
  <main>
    <Map />

    <Bar />
  </main>
</template>

<style scoped>
main {
  display: flex;
  width: 100%;
  height: 100%;
}
</style>
