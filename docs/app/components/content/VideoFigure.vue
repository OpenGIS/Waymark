<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    src: string;
    poster?: string;
    caption?: string;
    label?: string;
    width?: number | string;
    height?: number | string;
  }>(),
  {
    poster: undefined,
    caption: undefined,
    label: undefined,
    width: undefined,
    height: undefined,
  },
);

const resolvedSrc = computed(() => withAssetBase(props.src));
const resolvedPoster = computed(() =>
  props.poster ? withAssetBase(props.poster) : undefined,
);
</script>

<template>
  <figure class="video-figure">
    <video
      controls
      playsinline
      preload="metadata"
      :poster="resolvedPoster"
      :width="width"
      :height="height"
      :aria-label="label || caption || undefined"
    >
      <source :src="resolvedSrc" type="video/mp4" />
      Your browser doesn't support HTML video.
      <a :href="resolvedSrc">Download the video</a>.
    </video>
    <figcaption v-if="caption || $slots.default">
      <slot>{{ caption }}</slot>
    </figcaption>
  </figure>
</template>
