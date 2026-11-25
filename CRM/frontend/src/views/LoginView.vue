<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import { auth } from '../store/auth'

const router = useRouter()

const form = reactive({ email: '', password: '' })
const error = ref('')
const loading = ref(false)

async function login() {
  error.value = ''

  if (!form.email || !form.password) {
    error.value = 'Email va parolni kiriting'
    return
  }

  loading.value = true
  try {
    const { data } = await api.post('/login', form)
    auth.user = data
    router.push('/users')
  } catch {
    error.value = "Email yoki parol noto'g'ri"
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="login-page">
    <form class="login-card" @submit.prevent="login">
      <div class="logo">Kadirov Inc</div>
      <p class="subtitle">Tizimga kirish</p>

      <div class="form-group">
        <label>Email<span>*</span></label>
        <input v-model="form.email" type="email" placeholder="lorem.inc@gmail.com" />
      </div>

      <div class="form-group">
        <label>Parol<span>*</span></label>
        <input v-model="form.password" type="password" placeholder="********" />
      </div>

      <div v-if="error" class="login-error">{{ error }}</div>

      <button class="btn" :disabled="loading">Kirish</button>
    </form>
  </div>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-card {
  width: 360px;
  padding: 36px 32px;
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
}

.logo {
  font-size: 22px;
  font-weight: 600;
  color: var(--blue);
  text-align: center;
}

.subtitle {
  margin: 6px 0 28px;
  text-align: center;
  color: var(--gray);
}

.login-error {
  margin-bottom: 14px;
  font-size: 12px;
  color: var(--red);
}

.btn {
  width: 100%;
  margin-top: 6px;
}
</style>
