<script setup>
import { ref, onMounted, onUnmounted, nextTick, watch } from 'vue'
import ImageGallery from './ImageGallery.vue'

const props = defineProps({
  book: {
    type: Object,
    required: true
  },
  hideControls: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'toggleRead', 'home'])

const currentPage = ref(0)
const totalPages = ref(1)
const viewMode = ref('text')
const textContent = ref(null)

const scaleFactor = ref(1)

const pageWidth = 220
const pageGap = 40
const columnWidth = pageWidth + pageGap

const calculatePages = () => {
  if (textContent.value) {
    const scrollWidth = textContent.value.scrollWidth
    totalPages.value = Math.ceil(scrollWidth / columnWidth) || 1
  }
}

const updateScale = () => {
  const maxW = window.innerWidth * 0.95
  const maxH = window.innerHeight * 0.95
  
  const scaleW = maxW / 300
  const scaleH = maxH / 420
  
  scaleFactor.value = Math.min(scaleW, scaleH, 1.5)
}

onMounted(() => {
  updateScale()
  window.addEventListener('resize', updateScale)
  
  nextTick(() => {
    calculatePages()
  })
  
  document.fonts.ready.then(() => {
    calculatePages()
  })
})

onUnmounted(() => {
  window.removeEventListener('resize', updateScale)
})

watch(() => props.book.content, () => {
  nextTick(() => {
    calculatePages()
    if (currentPage.value >= totalPages.value) {
      currentPage.value = Math.max(0, totalPages.value - 1)
    }
  })
})

watch(() => props.book.id, () => {
  currentPage.value = 0
  viewMode.value = 'text'
})

const nextPage = () => {
  if (currentPage.value < totalPages.value - 1) {
    currentPage.value++
  }
}

const prevPage = () => {
  if (currentPage.value > 0) {
    currentPage.value--
  }
}

const toggleView = () => {
  viewMode.value = viewMode.value === 'text' ? 'gallery' : 'text'
}

const toggleRead = () => {
  emit('toggleRead', props.book.id)
}
</script>

<template>
  <div class="book-overlay" @click.self="$emit('home')">
    <div class="book-wrapper" :style="{ transform: `scale(${scaleFactor})`, '--scale-factor': scaleFactor }">
      <template v-if="viewMode === 'text'">
        <div class="pagination-info">
          Page {{ currentPage + 1 }} sur {{ totalPages }}
        </div>
        <div class="book-background">
          <div class="book-content">
            <div class="text-viewport">
              <div 
                class="text-columns" 
                ref="textContent"
                :style="{ transform: `translateX(-${currentPage * columnWidth}px)` }"
              >
                {{ book.content }}
              </div>
            </div>
          </div>

          <button 
            v-if="currentPage > 0" 
            class="page-button prev-button" 
            @click="prevPage"
          ></button>
          <button 
            v-if="currentPage < totalPages - 1" 
            class="page-button next-button" 
            @click="nextPage"
          ></button>
        </div>
      </template>
      
      <div v-else class="gallery-view">
        <ImageGallery :images="book.images" />
      </div>

      <div v-if="!hideControls" class="controls">
        <button class="mc-button" @click="toggleRead">
          <span>
            {{ book.isRead ? 'Non lu' : 'Lu' }}
          </span>
        </button>
        <button v-if="book.images && book.images.length > 0" class="mc-button" @click="toggleView">
          <span>
            {{ viewMode === 'text' ? 'Galerie' : 'Livre' }}
          </span>
        </button>
        <button class="mc-button" @click="$emit('close')">
          <span>
            Retour
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.book-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: transparent;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.book-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  transform-origin: center;
}

.book-background {
  width: 292px;
  height: 360px;
  background-image: url('/assets/images/book_page.webp');
  background-size: cover;
  position: relative;
  padding: 24px;
  color: #000;
  box-sizing: border-box;
}

.book-content {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.text-viewport {
  width: 220px;
  flex-grow: 1;
  overflow: hidden;
  position: relative;
  margin-top: 4px;
  margin-bottom: 1em;
}

.text-columns {
  column-width: 220px;
  column-gap: 40px;
  height: 100%;
  white-space: pre-wrap;
  font-size: 1rem;
  line-height: 1;
  text-align: justify;
  text-justify: inter-word;
  hyphens: auto;
  overflow-wrap: break-word;
  word-break: break-word;
}

.gallery-view {
  width: 100%;
  height: 100%;
}

.page-button {
  height: 16px;
  aspect-ratio: 18 / 10;
  background-image: url('/assets/images/next_page.webp');
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  position: absolute;
  bottom: 24px;
  border: none;
  background-color: transparent;
  padding: 0;
  cursor: pointer;
}

.page-button:hover {
  background-image: url('/assets/images/next_page_hover.webp');
}

.prev-button {
  left: 48px;
  transform: scaleX(-1);
}

.prev-button:hover {
  background-image: url('/assets/images/next_page_hover.webp');
  transform: scaleX(-1);
}

.next-button {
  right: 56px;
}

.controls {
  display: flex;
  gap: 12px;
}

.mc-button {
background: #999 url('https://i.ibb.co/rb2TWXL/bgbtn.png') center / cover;
	image-rendering: pixelated;
	border: 1px solid #000;
}

.mc-button:hover {
  border: 1px solid #fff;
}

.mc-button>span {
  display: block;
  color: #ddd;
  font-size: 1rem;
  text-shadow: 1.4px 1.4px #000A;
  box-shadow: inset -2px -2px #0006, inset 2px 2px #FFF7;
  padding: 6px 14px;
}

.pagination-info {
  color: #ddd;
  font-size: 1rem;
  text-shadow: 1.4px 1.4px #000A;
}

.pagination-info.hidden {
  visibility: hidden;
}
</style>
