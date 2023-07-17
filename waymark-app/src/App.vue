<script setup>
import { mapData, waymarkConfig } from '@/data/waymark.js'
</script>

<template>
  <header>{{ JSON.stringify(waymarkConfig) }}</header>

  <main>{{ JSON.stringify(mapData) }}</main>
</template>

<style scoped></style>
