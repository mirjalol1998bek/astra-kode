<script setup>
defineProps({
  text: { type: String, default: "Rostan ham o'chirilsinmi ?" },
})

const emit = defineEmits(['yes', 'no'])
</script>

<template>
  <div class="overlay" @click.self="emit('no')">
    <div class="confirm">
      <span>{{ text }}</span>
      <button class="yes" @click="emit('yes')">Ha</button>
      <button class="no" @click="emit('no')">Yo'q</button>
    </div>
  </div>
</template>

<style scoped>
.overlay {
  position: fixed;
  inset: 0;
  z-index: 60;
}

.confirm {
  position: absolute;
  top: 24px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: center;
  gap: 24px;
  padding: 14px 20px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
  font-size: 13px;
  color: var(--dark);
}

.confirm button {
  width: 100px;
  height: 30px;
  border: none;
  border-radius: 4px;
  color: #fff;
  font-size: 12px;
  cursor: pointer;
}

.yes {
  background: var(--red);
}

.no {
  background: var(--green);
}

.confirm button:hover {
  opacity: 0.85;
}
</style>
