<script setup>
defineProps({
  overlay: Object
})
</script>

<template>
  <div>
    {{ JSON.stringify(overlay.feature.properties) }}
  </div>
</template>

<style scoped lang="less">
div {
  border: 1px solid red;
}
</style>
