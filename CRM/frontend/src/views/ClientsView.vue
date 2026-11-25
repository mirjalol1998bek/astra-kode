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

const clients = ref([])
const companies = ref([])
const loading = ref(false)

const filters = reactive({
  companyId: '',
  name: '',
  email: '',
  company: '',
  date: '',
})

const showModal = ref(false)
const editId = ref(null)
const form = reactive({ name: '', email: '', companyId: '' })
const errors = ref({})
const saving = ref(false)

const deleteId = ref(null)

let requestId = 0

async function loadClients() {
  const current = ++requestId
  loading.value = true
  try {
    const { data } = await api.get('/clients', { params: filters })
    if (current === requestId) clients.value = data
  } finally {
    if (current === requestId) loading.value = false
  }
}

async function loadCompanies() {
  const { data } = await api.get('/companies')
  companies.value = data
}

watch(filters, debounce(loadClients))

onMounted(() => {
  loadClients()
  loadCompanies()
})

function openAdd() {
  editId.value = null
  form.name = ''
  form.email = ''
  form.companyId = ''
  errors.value = {}
  showModal.value = true
}

function openEdit(client) {
  editId.value = client.id
  form.name = client.name
  form.email = client.email
  form.companyId = client.company?.id || ''
  errors.value = {}
  showModal.value = true
}

async function save() {
  saving.value = true
  errors.value = {}
  try {
    if (editId.value) {
      await api.put(`/clients/${editId.value}`, form)
      showToast("Ma'lumot muvaffaqiyatli o'zgartirildi")
    } else {
      await api.post('/clients', form)
      showToast("Mijoz muvaffaqiyatli qo'shildi")
    }
    showModal.value = false
    loadClients()
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
    await api.delete(`/clients/${id}`)
    showToast("Mijoz o'chirildi")
    loadClients()
  } catch {
    showToast('Xatolik yuz berdi', 'error')
  }
}
</script>

<template>
  <PageHeader button-text="Mijoz qo'shish" @add="openAdd">
    <div class="company-filter">
      Kompaniya:
      <select v-model="filters.companyId">
        <option value="">Hammasi</option>
        <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
      </select>
    </div>
  </PageHeader>

  <div class="search-row">
    <SearchInput v-model="filters.name" placeholder="Mijozlar qidirish" />
    <SearchInput v-model="filters.email" placeholder="Email qidirish" />
    <SearchInput v-model="filters.company" placeholder="Ishlash joyi qidirish" />
    <SearchInput v-model="filters.date" placeholder="So'ngi faollikni qidirish" date />
  </div>

  <div class="table-card">
    <table class="table">
      <thead>
        <tr>
          <th style="padding-left: 32px">Ism</th>
          <th>Email</th>
          <th>Ishlash joyi</th>
          <th>So'ngi faollik</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in clients" :key="client.id">
          <td style="padding-left: 32px">
            <div class="name-cell">
              <UserAvatar :name="client.name" />
              {{ client.name }}
            </div>
          </td>
          <td>{{ client.email }}</td>
          <td>
            <span v-if="client.company" class="company">{{ client.company.name }}</span>
            <span v-else class="no-company">-</span>
          </td>
          <td>{{ timeAgo(client.lastActivity) }}</td>
          <td class="actions">
            <button class="icon-btn edit" title="O'zgartirish" @click="openEdit(client)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
              </svg>
            </button>
            <button class="icon-btn delete" title="O'chirish" @click="deleteId = client.id">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading && clients.length === 0" class="empty">Hech narsa topilmadi</div>
  </div>

  <AppModal
    v-if="showModal"
    :title="editId ? 'Mijozni o\'zgartirish' : 'Mijoz qo\'shish'"
    @close="showModal = false"
  >
    <form @submit.prevent="save">
      <div class="form-group">
        <label>Ismi<span>*</span></label>
        <input v-model="form.name" :class="{ invalid: errors.name }" placeholder="Alexander" />
        <div v-if="errors.name" class="error">{{ errors.name }}</div>
      </div>
      <div class="form-group">
        <label>Email<span>*</span></label>
        <input v-model="form.email" :class="{ invalid: errors.email }" placeholder="lorem.inc@gmail.com" />
        <div v-if="errors.email" class="error">{{ errors.email }}</div>
      </div>
      <div class="form-group">
        <label>Ishlash joyi<span>*</span></label>
        <select v-model="form.companyId" :class="{ invalid: errors.company }">
          <option value="" disabled>Kompaniyani tanlang</option>
          <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
        <div v-if="errors.company" class="error">{{ errors.company }}</div>
      </div>
      <div class="form-footer">
        <button class="btn small" :disabled="saving">
          {{ editId ? 'Saqlash' : "Qo'shish" }}
        </button>
      </div>
    </form>
  </AppModal>

  <ConfirmBox v-if="deleteId" @yes="remove" @no="deleteId = null" />
</template>

<style scoped>
.company-filter {
  font-size: 12px;
  color: var(--gray);
}

.company-filter select {
  border: none;
  outline: none;
  font-size: 12px;
  color: var(--blue);
  background: none;
  cursor: pointer;
}

.company {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 4px;
  background: #e8f5fe;
  color: var(--blue);
  font-size: 12px;
}

.no-company {
  color: var(--gray);
}

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
