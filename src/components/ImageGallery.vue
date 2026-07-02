<script setup>
import { ref } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true
  }
})

const currentIndex = ref(0)

const nextImage = () => {
  if (currentIndex.value < props.images.length - 1) {
    currentIndex.value++
  }
}

const prevImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}
</script>

<template>
  <div class="gallery-container">
    <button 
      class="nav-btn prev-btn" 
      @click="prevImage" 
      :disabled="currentIndex === 0"
      :class="{ hidden: currentIndex === 0 }"
    ></button>
    
    <div class="image-wrapper">
      <img :src="images[currentIndex]" class="gallery-image" alt="Gallery image" />
    </div>

    <button 
      class="nav-btn next-btn" 
      @click="nextImage" 
      :disabled="currentIndex === images.length - 1"
      :class="{ hidden: currentIndex === images.length - 1 }"
    ></button>
  </div>
</template>

<style scoped>
.gallery-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  position: relative;
}

.image-wrapper {
  width: 80%;
  height: 80%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
  border: 4px solid #373737;
}

.gallery-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.nav-btn {
  width: 32px;
  height: 32px;
  background-image: url('/assets/images/forward.webp');
  background-size: cover;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
}

.nav-btn:hover {
  background-image: url('/assets/images/forward_hover.webp');
}

.prev-btn {
  left: 10px;
  transform: translateY(-50%) scaleX(-1);
}

.prev-btn:hover {
  background-image: url('/assets/images/forward_hover.webp');
  transform: translateY(-50%) scaleX(-1);
}

.next-btn {
  right: 10px;
}

.hidden {
  opacity: 0;
  pointer-events: none;
}
</style>
