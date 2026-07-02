<script setup>
import { RouterView, useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const openInventory = () => {
  if (route.name === 'home') {
    router.push('/inventory')
  }
}
</script>

<template>
  <svg class="bg-svg" viewBox="0 0 2560 1440" preserveAspectRatio="xMidYMid slice">
    <image href="/assets/images/sohalia_bedroom.webp" width="2560" height="1440" />
    <rect 
      v-if="route.name === 'home'"
      class="svg-hitbox" 
      x="1160" y="820" width="240" height="240" 
      @click="openInventory"
    />
  </svg>
  <div class="content-overlay">
    <RouterView />
  </div>
</template>

<style>
#app {
  width: 100vw;
  height: 100vh;
  position: relative;
  overflow: hidden;
}

.bg-svg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.svg-hitbox {
  fill: transparent;
  cursor: pointer;
}

.content-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 2;
  pointer-events: none;
}

.content-overlay > * {
  pointer-events: auto;
}
</style>
