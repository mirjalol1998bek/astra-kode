<script setup>
import { computed } from 'vue'

const props = defineProps({
  name: { type: String, default: '' },
  size: { type: Number, default: 24 },
})

const colors = ['#109cf1', '#f7685b', '#2ed47a', '#ffb946', '#885af8', '#4fb4a9', '#e17c4f']

const letters = computed(() => {
  const parts = props.name.trim().split(' ').filter(Boolean)
  return parts.slice(0, 2).map((p) => p[0].toUpperCase()).join('')
})

// bir xil ismga doim bir xil rang chiqishi uchun
const color = computed(() => {
  let sum = 0
  for (const ch of props.name) sum += ch.charCodeAt(0)
  return colors[sum % colors.length]
})
</script>

<template>
  <div
    class="avatar"
    :style="{
      width: size + 'px',
      height: size + 'px',
      background: color,
      fontSize: size * 0.4 + 'px',
    }"
  >
    {{ letters }}
  </div>
</template>

<style scoped>
.avatar {
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 500;
  flex-shrink: 0;
}
</style>
