<script setup>
import { computed } from 'vue'
import BookUI from './BookUI.vue'

const props = defineProps({
  book: {
    type: Object,
    required: true
  }
})

const previewBook = computed(() => {
  return {
    ...props.book,
    id: 'preview'
  }
})
</script>

<template>
  <div class="live-preview-container">
    <BookUI :book="previewBook" @close="() => {}" @toggleRead="() => {}" />
  </div>
</template>

<style scoped>
.live-preview-container {
  width: 100%;
  height: 100%;
  position: relative;
}
</style>
