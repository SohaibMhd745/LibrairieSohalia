<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  book: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['update'])

const localBook = ref({ ...props.book })
const imageInput = ref('')

watch(localBook, (newVal) => {
  emit('update', newVal)
}, { deep: true })

const addImage = () => {
  if (imageInput.value.trim()) {
    localBook.value.images.push(imageInput.value.trim())
    imageInput.value = ''
  }
}

const removeImage = (index) => {
  localBook.value.images.splice(index, 1)
}

const publish = async () => {
  try {
    const res = await fetch('/api/save-book.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(localBook.value)
    })
    const data = await res.json()
    if (data.success) {
      alert('Livre publié avec succès!')
      localBook.value = { title: '', date: new Date().toISOString().split('T')[0], content: '', images: [] }
    } else {
      alert('Erreur lors de la publication.')
    }
  } catch (error) {
    console.error('Publish error', error)
    alert('Erreur réseau.')
  }
}
</script>

<template>
  <div class="editor-container">
    <h2>Nouvelle Entrée</h2>
    
    <div class="form-group">
      <label>Titre</label>
      <input v-model="localBook.title" type="text" placeholder="Titre de l'histoire" />
    </div>

    <div class="form-group">
      <label>Date</label>
      <input v-model="localBook.date" type="date" />
    </div>

    <div class="form-group">
      <label>Images (URLs)</label>
      <div class="image-input">
        <input v-model="imageInput" type="text" placeholder="URL de l'image (ex: imgur)" @keyup.enter="addImage" />
        <button @click="addImage">Ajouter</button>
      </div>
      <ul class="image-list">
        <li v-for="(img, idx) in localBook.images" :key="idx">
          {{ img }} <button @click="removeImage(idx)">X</button>
        </li>
      </ul>
    </div>

    <div class="form-group">
      <label>Contenu</label>
      <textarea v-model="localBook.content" rows="15" placeholder="Écrivez l'histoire ici..."></textarea>
    </div>

    <button class="publish-btn" @click="publish">Publier</button>
  </div>
</template>

<style scoped>
.editor-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

h2 {
  font-family: 'MinecraftDefault', sans-serif;
  color: #fff;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

label {
  font-weight: bold;
}

input, textarea {
  padding: 8px;
  background-color: #111;
  border: 1px solid #555;
  color: #fff;
  font-family: sans-serif;
}

textarea {
  resize: vertical;
}

.image-input {
  display: flex;
  gap: 8px;
}

.image-input input {
  flex: 1;
}

.image-input button {
  padding: 0 16px;
  background-color: #444;
  color: #fff;
  border: 1px solid #666;
}

.image-list {
  list-style: none;
  padding: 0;
}

.image-list li {
  display: flex;
  justify-content: space-between;
  background-color: #2a2a2a;
  padding: 4px 8px;
  margin-top: 4px;
}

.image-list button {
  color: red;
}

.publish-btn {
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  font-weight: bold;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
}

.publish-btn:hover {
  background-color: #45a049;
}
</style>
