<script setup>
defineProps({
  buttonText: String,
})

const emit = defineEmits(['add'])
</script>

<template>
  <div class="page-header">
    <slot />
    <button v-if="buttonText" class="btn" @click="emit('add')">{{ buttonText }}</button>
  </div>
</template>

<style scoped>
.page-header {
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 14px;
  margin-bottom: 12px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.page-header .btn {
  margin-left: auto;
}
</style>
