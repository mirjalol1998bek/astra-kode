<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import api from '../api'
import { showToast } from '../store/toast'
import { timeAgo } from '../utils/date'
import { debounce } from '../utils/debounce'
import PageHeader from '../components/PageHeader.vue'
import SearchInput from '../components/SearchInput.vue'
import UserAvatar from '../components/UserAvatar.vue'
import AppModal from '../components/AppModal.vue'
import ConfirmBox from '../components/ConfirmBox.vue'

const companies = ref([])
const loading = ref(false)

const filters = reactive({
  name: '',
  email: '',
  date: '',
})

const showModal = ref(false)
const editId = ref(null)
const form = reactive({ name: '', email: '', password: '' })
const errors = ref({})
const saving = ref(false)

const deleteId = ref(null)

let requestId = 0

async function loadCompanies() {
  const current = ++requestId
  loading.value = true
  try {
    const { data } = await api.get('/companies', { params: filters })
    if (current === requestId) companies.value = data
  } finally {
    if (current === requestId) loading.value = false
  }
}

watch(filters, debounce(loadCompanies))
onMounted(loadCompanies)

function openAdd() {
  editId.value = null
  form.name = ''
  form.email = ''
  form.password = ''
  errors.value = {}
  showModal.value = true
}

function openEdit(company) {
  editId.value = company.id
  form.name = company.name
  form.email = company.email
  form.password = ''
  errors.value = {}
  showModal.value = true
}

async function save() {
  saving.value = true
  errors.value = {}
  try {
    if (editId.value) {
      await api.put(`/companies/${editId.value}`, form)
      showToast("Ma'lumot muvaffaqiyatli o'zgartirildi")
    } else {
      await api.post('/companies', form)
      showToast("Kompaniya muvaffaqiyatli qo'shildi")
    }
    showModal.value = false
    loadCompanies()
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors
    } else if (e.response?.status !== 401) {
      showToast('Xatolik yuz berdi', 'error')
    }
  } finally {
    saving.value = false
  }
}

async function remove() {
  const id = deleteId.value
  deleteId.value = null
  try {
    await api.delete(`/companies/${id}`)
    showToast("Kompaniya o'chirildi")
    loadCompanies()
  } catch {
    showToast('Xatolik yuz berdi', 'error')
  }
}
</script>

<template>
  <PageHeader button-text="Kompaniya qo'shish" @add="openAdd" />

  <div class="search-row">
    <SearchInput v-model="filters.name" placeholder="Kompaniya qidirish" />
    <SearchInput v-model="filters.email" placeholder="Email qidirish" />
    <SearchInput v-model="filters.date" placeholder="So'ngi faollikni qidirish" date />
  </div>

  <div class="table-card">
    <table class="table">
      <thead>
        <tr>
          <th style="padding-left: 32px">Kompaniya nomi</th>
          <th>Email</th>
          <th>Parol</th>
          <th>So'ngi faollik</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="company in companies" :key="company.id">
          <td style="padding-left: 32px">
            <div class="name-cell">
              <UserAvatar :name="company.name" />
              {{ company.name }}
            </div>
          </td>
          <td>{{ company.email }}</td>
          <td>********</td>
          <td>{{ timeAgo(company.lastActivity) }}</td>
          <td class="actions">
            <button class="icon-btn edit" title="O'zgartirish" @click="openEdit(company)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
              </svg>
            </button>
            <button class="icon-btn delete" title="O'chirish" @click="deleteId = company.id">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading && companies.length === 0" class="empty">Hech narsa topilmadi</div>
  </div>

  <AppModal
    v-if="showModal"
    :title="editId ? 'Kompaniya o\'zgartirish' : 'Kompaniya qo\'shish'"
    @close="showModal = false"
  >
    <form @submit.prevent="save">
      <div class="form-group">
        <label>Nomi<span>*</span></label>
        <input v-model="form.name" :class="{ invalid: errors.name }" placeholder="Alexander" />
        <div v-if="errors.name" class="error">{{ errors.name }}</div>
      </div>
      <div class="form-group">
        <label>Email<span>*</span></label>
        <input v-model="form.email" :class="{ invalid: errors.email }" placeholder="lorem.inc@gmail.com" />
        <div v-if="errors.email" class="error">{{ errors.email }}</div>
      </div>
      <div class="form-group">
        <label>Parol<span v-if="!editId">*</span></label>
        <input
          v-model="form.password"
          type="password"
          :class="{ invalid: errors.password }"
          :placeholder="editId ? 'O\'zgartirmasangiz bo\'sh qoldiring' : '12345678'"
        />
        <div v-if="errors.password" class="error">{{ errors.password }}</div>
      </div>
      <div class="form-footer">
        <button class="btn small" :disabled="saving">
          {{ editId ? 'Saqlash' : "Qo'shish" }}
        </button>
      </div>
    </form>
  </AppModal>

  <ConfirmBox
    v-if="deleteId"
    text="Kompaniya o'chirilsinmi ?"
    @yes="remove"
    @no="deleteId = null"
  />
</template>

<style scoped>
.form-footer {
  text-align: center;
  margin-top: 20px;
}

.btn.small {
  height: 30px;
  padding: 0 36px;
  font-size: 12px;
}
</style>
