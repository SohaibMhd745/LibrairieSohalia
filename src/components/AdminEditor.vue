<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  book: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['update', 'back'])

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

const updateCaption = (idx, caption) => {
  const img = localBook.value.images[idx]
  if (typeof img === 'string') {
    localBook.value.images[idx] = { url: img, caption }
  } else {
    img.caption = caption
  }
}

const removeImage = (index) => {
  localBook.value.images.splice(index, 1)
}

const saveBook = async () => {
  try {
    const res = await fetch('/api/save-book.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(localBook.value)
    })
    const data = await res.json()
    if (data.success) {
      alert('Livre sauvegardé avec succès!')
      emit('back')
    } else {
      alert('Erreur lors de la sauvegarde.')
    }
  } catch (error) {
    console.error('Save error', error)
    alert('Erreur réseau.')
  }
}

const toggleArchive = async () => {
  const actionName = localBook.value.isArchived ? 'désarchiver' : 'archiver';
  if (confirm(`Voulez-vous vraiment ${actionName} ce livre ?`)) {
    localBook.value.isArchived = !localBook.value.isArchived
    await saveBook()
  }
}
</script>

<template>
  <div class="editor-container">
    <div class="header">
      <button class="back-btn" @click="$emit('back')">Retour</button>
      <h2>{{ localBook.id ? 'Éditer le livre' : 'Nouveau livre' }}</h2>
    </div>
    
    <div class="form-group">
      <label>Titre</label>
      <input v-model="localBook.title" type="text" placeholder="Titre du livre" />
    </div>

    <div class="form-group">
      <label>Date</label>
      <input v-model="localBook.date" type="date" />
    </div>

    <div class="form-group">
      <label>Images</label>
      <div class="image-input">
        <input v-model="imageInput" type="text" placeholder="URL de l'image" @keyup.enter="addImage" />
        <button @click="addImage">Ajouter</button>
      </div>
      <div class="image-previews">
        <div v-for="(img, idx) in localBook.images" :key="idx" class="image-preview-item">
          <img :src="typeof img === 'string' ? img : img.url" class="thumb" />
          <div class="image-preview-info">
            <span class="img-url">{{ typeof img === 'string' ? img : img.url }}</span>
            <input 
              type="text" 
              placeholder="Légende (optionnel)" 
              :value="typeof img === 'string' ? '' : img.caption"
              @input="updateCaption(idx, $event.target.value)"
            />
          </div>
          <button class="remove-btn" @click="removeImage(idx)">X</button>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>Contenu</label>
      <textarea v-model="localBook.content" rows="40" placeholder="Contenu du livre..."></textarea>
    </div>

    <div class="actions">
      <button class="publish-button" @click="saveBook">{{ localBook.id ? 'Sauvegarder' : 'Créer' }}</button>
      <button v-if="localBook.id" class="archive-button" @click="toggleArchive">
        {{ localBook.value?.isArchived || localBook.isArchived ? 'Désarchiver' : 'Archiver' }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.editor-container {
  width: 80%;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.header {
  display: flex;
  align-items: center;
  gap: 16px;
}

.back-btn {
  padding: 8px 16px;
  background-color: #555;
  color: #fff;
  border: none;
  cursor: pointer;
}

.back-btn:hover {
  background-color: #666;
}

h2 {
  font-family: 'MinecraftDefault', sans-serif;
  color: #fff;
  margin: 0;
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

.image-previews {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-top: 8px;
  max-height: 250px;
  overflow-y: auto;
  padding-right: 4px;
}

.image-preview-item {
  display: flex;
  align-items: center;
  background-color: #2a2a2a;
  padding: 8px;
  gap: 12px;
}

.thumb {
  width: 120px;
  height: 68px;
  object-fit: cover;
  border: 1px solid #444;
}

.image-preview-info {
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 4px;
}

.img-url {
  font-size: 0.8rem;
  color: #aaa;
  word-break: break-all;
}

.remove-btn {
  color: #ff5555;
  background: transparent;
  border: none;
  font-weight: bold;
  cursor: pointer;
  padding: 8px;
}

.remove-btn:hover {
  color: #ff0000;
}

.actions {
  display: flex;
  gap: 16px;
  margin-top: 20px;
}

.publish-button {
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  font-weight: bold;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  flex: 1;
}

.publish-button:hover {
  background-color: #45a049;
}

.archive-button {
  padding: 12px;
  background-color: #f44336;
  color: white;
  font-weight: bold;
  font-size: 1.1rem;
  border: none;
  cursor: pointer;
  flex: 1;
}

.archive-button:hover {
  background-color: #d32f2f;
}
</style>
