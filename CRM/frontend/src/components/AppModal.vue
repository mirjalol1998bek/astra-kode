<script setup>
defineProps({
  title: String,
})

const emit = defineEmits(['close'])
</script>

<template>
  <div class="overlay" @mousedown.self="emit('close')">
    <div class="modal">
      <div class="modal-header">
        {{ title }}
        <button class="close" @click="emit('close')">&times;</button>
      </div>
      <div class="modal-body">
        <slot />
      </div>
    </div>
  </div>
</template>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.7);
}

.modal {
  width: 330px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
}

.modal-header {
  position: relative;
  padding: 16px;
  text-align: center;
  font-size: 13px;
  font-weight: 600;
  color: var(--dark);
  border-bottom: 1px solid #d3d8dd;
}

.close {
  position: absolute;
  right: 12px;
  top: 10px;
  border: none;
  background: none;
  font-size: 20px;
  color: var(--gray);
  cursor: pointer;
}

.modal-body {
  padding: 20px 22px 18px;
}
</style>
