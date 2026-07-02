<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import BookUI from './BookUI.vue'

const books = ref([])
const selectedBook = ref(null)
const router = useRouter()

const goHome = () => {
  router.push('/')
}

onMounted(async () => {
  try {
    const res = await fetch('/api/get-books.php')
    const data = await res.json()
    books.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to load books', error)
  }
})

const openBook = (book) => {
  selectedBook.value = book
}

const closeBook = () => {
  selectedBook.value = null
}

const handleToggleRead = async (id) => {
  try {
    const res = await fetch('/api/toggle-read.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id })
    })
    const data = await res.json()
    if (data.success) {
      const book = books.value.find(b => b.id === id)
      if (book) {
        book.isRead = !book.isRead
      }
    }
  } catch (error) {
    console.error('Failed to toggle read status', error)
  }
}
</script>

<template>
  <div class="inventory-container" @click.self="goHome">
    <div v-if="!selectedBook" class="inventory-gui">
      <div class="grid">
        <div 
          v-for="book in books" 
          :key="book.id" 
          class="slot"
          @click="openBook(book)"
        >
          <img 
            src="/assets/images/book_and_quill.webp" 
            alt="Book" 
            class="item"
            :class="{ 'read': book.isRead }"
          />
          <div class="tooltip">{{ book.title }}</div>
        </div>
        <div v-for="n in 54 - books.length" :key="'empty-'+n" class="slot empty"></div>
      </div>
    </div>
    
    <BookUI 
      v-if="selectedBook" 
      :book="selectedBook" 
      @close="closeBook"
      @home="goHome"
      @toggleRead="handleToggleRead"
    />
  </div>
</template>

<style scoped>
.inventory-container {
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}

.inventory-gui {
  background-color: #c6c6c6;
  border: 4px solid #fff;
  border-right-color: #555;
  border-bottom-color: #555;
  padding: 8px;
  border-radius: 6px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(9, 36px);
  grid-template-rows: repeat(6, 36px);
}

.slot {
  width: 36px;
  height: 36px;
  background-color: #8b8b8b;
  border: 2px solid #373737;
  border-right-color: #fff;
  border-bottom-color: #fff;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.slot:hover {
  background-color: #c0c0c0;
}

.slot.empty {
  cursor: default;
}

.item {
  width: 32px;
  height: 32px;
  image-rendering: pixelated;
}

.item.read {
  background-color: #646464;
}

.tooltip {
  display: none;
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(27, 12, 27, 0.95);
  color: #ffffff;
  border: 2px solid rgba(41, 8, 89, 0.5);
  padding: 4px 8px;
  white-space: nowrap;
  z-index: 10;
  font-size: 24px;
  font-style: italic;
  pointer-events: none;
  text-shadow: 2px 2px 0 #3f3f3f;
}

.slot:hover .tooltip {
  display: block;
}
</style>
