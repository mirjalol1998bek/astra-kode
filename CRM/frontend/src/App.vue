<script setup>
import { useRoute } from 'vue-router'
import AppSidebar from './components/AppSidebar.vue'
import AppToast from './components/AppToast.vue'

const route = useRoute()
</script>

<template>
  <router-view v-if="route.meta.guest" />

  <div v-else class="layout">
    <AppSidebar />
    <main class="content">
      <router-view />
    </main>
  </div>

  <AppToast />
</template>

<style scoped>
.layout {
  display: flex;
  min-height: 100vh;
}

.content {
  flex: 1;
  min-width: 0;
  padding: 10px 24px 30px;
}
</style>
