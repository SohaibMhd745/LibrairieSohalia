<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import BookUI from './BookUI.vue'
import InventoryGrid from './InventoryGrid.vue'

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
    <InventoryGrid 
      v-if="!selectedBook" 
      :books="books" 
      @select="openBook"
    />
    
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
</style>
