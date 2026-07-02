<script setup>
import { ref } from 'vue'
import AdminEditor from './components/AdminEditor.vue'
import LivePreview from './components/LivePreview.vue'

const currentBook = ref({
  title: '',
  date: new Date().toISOString().split('T')[0],
  content: '',
  images: []
})

const updateBook = (newData) => {
  currentBook.value = { ...newData }
}
</script>

<template>
  <div class="admin-layout">
    <div class="editor-pane">
      <AdminEditor :book="currentBook" @update="updateBook" />
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
  flex: 1;
  padding: 20px;
  border-right: 2px solid #444;
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
</style>
