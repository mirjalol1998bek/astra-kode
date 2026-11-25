<script setup>
import { ref } from 'vue'

const text = ref('')
const todos = ref([
  { id: 1, title: 'I will wake up at 8 in the morning' },
  { id: 2, title: 'I will practice html for 1 hour' },
  { id: 3, title: 'I will give time for 2 hours css' },
  { id: 4, title: 'Then I will have breakfast' },
])

function addTodo() {
  // bo'sh bo'lsa qo'shmaymiz
  if (text.value.trim() === '') return

  todos.value.unshift({
    id: Date.now(),
    title: text.value.trim(),
  })
  text.value = ''
}

function deleteTodo(id) {
  todos.value = todos.value.filter((item) => item.id !== id)
}
</script>

<template>
  <div class="card">
    <form class="form" @submit.prevent="addTodo">
      <input v-model="text" type="text" placeholder="Enter task..." />
      <button type="submit" class="add-btn">Add</button>
    </form>

    <ul class="list">
      <li v-for="todo in todos" :key="todo.id" class="item">
        <span>{{ todo.title }}</span>
        <button class="delete-btn" @click="deleteTodo(todo.id)">
          <svg width="12" height="14" viewBox="0 0 448 512" fill="currentColor">
            <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
          </svg>
        </button>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.card {
  width: 400px;
  background: #fff;
  border-radius: 8px;
  padding: 28px 18px;
}

.form {
  display: flex;
  justify-content: space-between;
  gap: 15px;
  margin-bottom: 45px;
}

.form input {
  flex: 1;
  padding: 8px 10px;
  border: 1px solid #bcc3cf;
  border-radius: 4px;
  font-size: 14px;
  outline: none;
}

.form input:focus {
  border-color: #3d6ee0;
}

.add-btn {
  width: 70px;
  background: #4a72e8;
  color: #fff;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}

.add-btn:hover {
  background: #3a60d0;
}

.list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #cfdee5;
  border: 1px solid #b3c4cc;
  border-radius: 3px;
  padding: 4px 6px 4px 8px;
  margin-bottom: 6px;
  font-size: 13px;
  color: #333;
}

.delete-btn {
  width: 28px;
  height: 28px;
  background: #1f1fa0;
  color: #fff;
  border: none;
  border-radius: 3px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.delete-btn:hover {
  background: #15157a;
}
</style>
