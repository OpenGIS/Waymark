<script setup>
defineProps({
  typeData: Object,
  featureType: String
})
</script>

<template>
  <!-- Icon -->
  <div
    v-if="featureType == 'marker'"
    class="waymark-marker"
    :class="typeData.iconData.className"
    v-html="typeData.iconData.html"
    :style="`width:${typeData.iconData.iconSize[0]}px;height:${typeData.iconData.iconSize[1]}px`"
  />
</template>
