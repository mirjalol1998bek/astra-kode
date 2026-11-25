<script setup>
import { useRouter } from 'vue-router'
import api from '../api'
import { auth } from '../store/auth'
import PageHeader from '../components/PageHeader.vue'
import UserAvatar from '../components/UserAvatar.vue'

const router = useRouter()

async function logout() {
  await api.post('/logout')
  auth.user = null
  router.push('/login')
}
</script>

<template>
  <PageHeader>
    <span class="title">Sozlamalar</span>
  </PageHeader>

  <div class="card" v-if="auth.user">
    <UserAvatar :name="auth.user.name" :size="64" />
    <div class="info">
      <div class="name">{{ auth.user.name }}</div>
      <div class="email">{{ auth.user.email }}</div>
    </div>
    <button class="logout" @click="logout">Tizimdan chiqish</button>
  </div>
</template>

<style scoped>
.title {
  font-size: 15px;
  font-weight: 500;
  color: var(--dark);
}

.card {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 24px 32px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
}

.name {
  font-size: 16px;
  font-weight: 500;
  color: var(--dark);
}

.email {
  color: var(--gray);
}

.logout {
  margin-left: auto;
  height: 38px;
  padding: 0 20px;
  border: 1px solid var(--red);
  border-radius: 4px;
  background: #fff;
  color: var(--red);
  cursor: pointer;
}

.logout:hover {
  background: var(--red);
  color: #fff;
}
</style>
