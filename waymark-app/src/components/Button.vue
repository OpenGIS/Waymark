<script setup>
import { computed } from 'vue'

const props = defineProps({ icon: { type: String } })

const iconClass = computed(() => {
  if (props.icon && props.icon.indexOf('ion') === 0) {
    return `ion ${props.icon}`
  } else {
    return `fa ${props.icon}`
  }
})
</script>

<template>
  <div class="button">
    <i v-if="icon" :class="`${iconClass}`"></i>
    <slot />
  </div>
</template>

<style>
.button {
  display: block-inline;
  margin: 10px;
  padding: 10px;
  float: right;
  color: white;
  background: blue;
  border: 1px solid red;
}
</style>
