<script setup>
import { computed } from 'vue'

const props = defineProps({
  icon: {
    type: String,
    default: null
  }
})

const iconClass = computed(() => {
  if (typeof props.icon == 'string' && props.icon.indexOf('ion') === 0) {
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
  padding: 12px;
  width: 15px;
  font-size: 160%;
  /*  line-height: 1;*/
  color: #000;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
}
</style>
