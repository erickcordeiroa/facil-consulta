<template>
  <button :class="[variant]" :disabled="disabled">
    <slot />
  </button>
</template>

<script setup>
defineProps({
  variant: {
    type: String,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});
</script>
