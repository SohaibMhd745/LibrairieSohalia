<script setup>
defineProps({
  books: {
    type: Array,
    required: true,
    default: () => []
  }
})
defineEmits(['select'])
</script>

<template>
  <div class="inventory-gui">
    <div class="grid">
      <div 
        v-for="book in books" 
        :key="book.id" 
        class="slot"
        @click="$emit('select', book)"
      >
        <img 
          src="/assets/images/book_and_quill.webp" 
          alt="Book" 
          class="item"
          :class="{ 'read': book.isRead, 'archived': book.isArchived }"
        />
        <div class="tooltip">{{ book.title }}</div>
      </div>
      <div v-for="n in Math.max(0, 54 - books.length)" :key="'empty-'+n" class="slot empty"></div>
    </div>
  </div>
</template>

<style scoped>
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

.item.read, .item.archived {
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
