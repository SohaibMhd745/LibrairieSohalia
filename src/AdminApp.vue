<script setup>
import { ref, onMounted } from 'vue'
import AdminEditor from './components/AdminEditor.vue'
import LivePreview from './components/LivePreview.vue'
import InventoryGrid from './components/InventoryGrid.vue'

const viewMode = ref('list')
const books = ref([])

const fetchBooks = async () => {
  try {
    const res = await fetch('/api/get-books.php?all=1')
    const data = await res.json()
    books.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Failed to load books', error)
  }
}

onMounted(() => {
  fetchBooks()
})

const getEmptyBook = () => ({
  title: '',
  date: new Date().toISOString().split('T')[0],
  content: '',
  images: []
})

const currentBook = ref(getEmptyBook())

const updateBook = (newData) => {
  currentBook.value = { ...newData }
}

const handleSelect = (book) => {
  currentBook.value = { ...book }
  viewMode.value = 'edit'
}

const createNew = () => {
  currentBook.value = getEmptyBook()
  viewMode.value = 'edit'
}

const handleBack = () => {
  fetchBooks()
  viewMode.value = 'list'
}
</script>

<template>
  <div class="admin-layout">
    <div class="editor-pane">
      <div v-if="viewMode === 'list'" class="selector-view">
        <InventoryGrid :books="books" @select="handleSelect" />
        <button @click="createNew" class="btn-new">Nouveau livre</button>
      </div>
      <AdminEditor v-else :book="currentBook" @update="updateBook" @back="handleBack" />
    </div>
    <div class="preview-pane">
      <LivePreview :book="currentBook" />
    </div>
  </div>
</template>

<style scoped>
.admin-layout {
  display: flex;
  width: 100vw;
  height: 100vh;
  background-color: #222;
  color: #eee;
}

.editor-pane {
  display: flex;
  align-items: center;
  justify-content: center;
  flex: 2;
  overflow-y: auto;
}

.preview-pane {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #333;
  position: relative;
}

.selector-view {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.btn-new {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

.btn-new:hover {
  background-color: #45a049;
}
</style>
