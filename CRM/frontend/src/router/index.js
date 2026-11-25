import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
import { auth } from '../store/auth'

import LoginView from '../views/LoginView.vue'
import UsersView from '../views/UsersView.vue'
import CompaniesView from '../views/CompaniesView.vue'
import ClientsView from '../views/ClientsView.vue'
import SettingsView from '../views/SettingsView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: LoginView, meta: { guest: true } },
    { path: '/', redirect: '/users' },
    { path: '/users', component: UsersView },
    { path: '/companies', component: CompaniesView },
    { path: '/clients', component: ClientsView },
    { path: '/settings', component: SettingsView },
    { path: '/:pathMatch(.*)*', redirect: '/users' },
  ],
})

router.beforeEach(async (to) => {
  // sahifa birinchi ochilganda kim kirganini tekshirib olamiz
  if (!auth.checked) {
    try {
      const { data } = await axios.get('/api/me')
      auth.user = data
    } catch {
      auth.user = null
    }
    auth.checked = true
  }

  if (!to.meta.guest && !auth.user) {
    return '/login'
  }

  if (to.meta.guest && auth.user) {
    return '/users'
  }
})

export default router
