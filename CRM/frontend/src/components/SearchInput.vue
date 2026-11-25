<script setup>
import { ref } from 'vue'

const props = defineProps({
  placeholder: String,
  date: Boolean,
})

const model = defineModel()

// sana qidirishda placeholder ko'rinib turishi uchun focus bo'lgandagina date qilamiz
const type = ref('text')

function onFocus() {
  if (props.date) type.value = 'date'
}

function onBlur() {
  if (props.date && !model.value) type.value = 'text'
}
</script>

<template>
  <label class="search">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="7" />
      <path d="M20 20l-4-4" />
    </svg>
    <input
      v-model="model"
      :type="type"
      :placeholder="placeholder"
      @focus="onFocus"
      @blur="onBlur"
    />
  </label>
</template>

<style scoped>
.search {
  flex: 1;
  max-width: 220px;
  height: 38px;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 14px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  color: var(--gray);
  cursor: text;
}

.search input {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  font-size: 12px;
  color: var(--text);
  background: none;
}

.search input::placeholder {
  color: var(--gray);
}
</style>
