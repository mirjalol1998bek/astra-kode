<script setup>
import { toast } from '../store/toast'
</script>

<template>
  <transition name="fade">
    <div v-if="toast.text" class="toast" :class="toast.type">
      <svg v-if="toast.type === 'success'" width="18" height="18" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="12" r="10" fill="#fff" />
        <path d="M7.5 12.5l3 3 6-6.5" stroke="#2ed47a" stroke-width="2" fill="none" />
      </svg>
      {{ toast.text }}
    </div>
  </transition>
</template>

<style scoped>
.toast {
  position: fixed;
  top: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 100;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 22px;
  border-radius: 4px;
  font-size: 12px;
  color: #fff;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
}

.toast.success {
  background: var(--green);
}

.toast.error {
  background: var(--red);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
