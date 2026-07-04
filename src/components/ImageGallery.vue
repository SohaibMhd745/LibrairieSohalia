<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true
  }
})

const currentIndex = ref(0)

const currentImage = computed(() => {
  const img = props.images[currentIndex.value]
  if (typeof img === 'string') {
    return { url: img, caption: '' }
  }
  return img
})

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
    <div class="image-wrapper">
      <img :src="currentImage.url" class="gallery-image" alt="Gallery image" />
    </div>

    <div class="image-caption">
      {{ currentImage.caption ? currentImage.caption : 'Aucune description.' }}
    </div>

    <div class="gallery-nav">
      <button 
        class="nav-btn prev-btn" 
        @click="prevImage" 
        :disabled="currentIndex === 0"
        :class="{ hidden: currentIndex === 0 }"
      ></button>
      
      <button 
        class="nav-btn next-btn" 
        @click="nextImage" 
        :disabled="currentIndex === images.length - 1"
        :class="{ hidden: currentIndex === images.length - 1 }"
      ></button>
    </div>
  </div>
</template>

<style scoped>
.gallery-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.image-wrapper {
  width: calc(min(85vw, 124vh) / var(--scale-factor, 1));
  aspect-ratio: 16 / 9;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.5);
  border: 2px solid #000;
}

.gallery-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.gallery-nav {
  display: flex;
  gap: 20px;
}

.nav-btn {
  height: 16px;
  aspect-ratio: 8 / 10;
  background-image: url('/assets/images/forward.webp');
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  border: none;
  background-color: transparent;
  cursor: pointer;
  padding: 0;
}

.nav-btn:hover {
  background-image: url('/assets/images/forward_hover.webp');
}

.prev-btn {
  transform: scaleX(-1);
}

.prev-btn:hover {
  background-image: url('/assets/images/forward_hover.webp');
  transform: scaleX(-1);
}

.hidden {
  opacity: 0;
  pointer-events: none;
}

.image-caption {
  text-align: center;
  max-width: 80%;
  font-size: 1rem;
  color: #ddd;
  text-shadow: 1.4px 1.4px #000A;
}
</style>
