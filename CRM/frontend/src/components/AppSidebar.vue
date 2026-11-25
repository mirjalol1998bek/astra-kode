<script setup>
import { ref } from 'vue'
import { auth } from '../store/auth'
import UserAvatar from './UserAvatar.vue'

const collapsed = ref(localStorage.getItem('menu') === 'closed')

function toggleMenu() {
  collapsed.value = !collapsed.value
  localStorage.setItem('menu', collapsed.value ? 'closed' : 'open')
}
</script>

<template>
  <aside class="sidebar" :class="{ collapsed }">
    <div class="logo">{{ collapsed ? 'K' : 'Kadirov Inc' }}</div>

    <div class="profile" v-if="auth.user">
      <UserAvatar :name="auth.user.name" :size="46" />
      <div class="profile-info" v-if="!collapsed">
        <div class="profile-name">{{ auth.user.name }}</div>
        <div class="profile-email">{{ auth.user.email }}</div>
      </div>
    </div>

    <nav class="menu">
      <router-link to="/users" class="menu-item" title="Foydalanuvchilar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
          <circle cx="12" cy="8" r="4" />
          <path d="M4 21c0-4 3.6-7 8-7s8 3 8 7" />
        </svg>
        <span v-if="!collapsed">Foydalanuvchilar</span>
      </router-link>

      <router-link to="/companies" class="menu-item" title="Kompaniyalar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
          <path d="M3 21V9l9-6 9 6v12z" />
          <path d="M9 21v-6h6v6" />
        </svg>
        <span v-if="!collapsed">Kompaniyalar</span>
      </router-link>

      <router-link to="/clients" class="menu-item" title="Mijozlar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
          <circle cx="9" cy="8" r="3.5" />
          <path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6" />
          <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14c2.4.7 4 2.9 4 6" />
        </svg>
        <span v-if="!collapsed">Mijozlar</span>
      </router-link>
    </nav>

    <nav class="menu">
      <router-link to="/settings" class="menu-item" title="Sozlamalar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <circle cx="5" cy="12" r="1.6" />
          <circle cx="12" cy="12" r="1.6" />
          <circle cx="19" cy="12" r="1.6" />
        </svg>
        <span v-if="!collapsed">Sozlamalar</span>
      </router-link>
    </nav>

    <button class="toggle" @click="toggleMenu">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <path d="M9 4v16" />
      </svg>
      <span v-if="!collapsed">Menyuni yopish</span>
    </button>
  </aside>
</template>

<style scoped>
.sidebar {
  position: sticky;
  top: 0;
  width: 256px;
  height: 100vh;
  display: flex;
  flex-direction: column;
  background: #fff;
  box-shadow: 6px 0 18px rgba(0, 0, 0, 0.06);
  transition: width 0.2s;
  flex-shrink: 0;
}

.sidebar.collapsed {
  width: 72px;
}

.logo {
  height: 60px;
  display: flex;
  align-items: center;
  padding: 0 24px;
  font-size: 18px;
  font-weight: 600;
  color: var(--blue);
  border-bottom: 1px solid var(--border);
}

.profile {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 22px 13px 22px 24px;
}

.collapsed .profile {
  padding: 22px 13px;
}

.profile-info {
  min-width: 0;
}

.profile-name {
  font-size: 14px;
  font-weight: 500;
  color: var(--dark);
}

.profile-email {
  font-size: 11px;
  color: var(--gray);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.menu {
  padding: 8px 0;
  border-bottom: 1px solid var(--border);
}

.menu:last-of-type {
  border-bottom: none;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 10px 24px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text);
  transition: color 0.15s;
}

.menu-item svg {
  color: var(--gray);
  flex-shrink: 0;
}

.menu-item:hover,
.menu-item.router-link-active,
.menu-item:hover svg,
.menu-item.router-link-active svg {
  color: var(--blue);
}

.toggle {
  margin-top: auto;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 24px;
  border: none;
  background: none;
  font-size: 11px;
  color: var(--gray);
  cursor: pointer;
  text-align: left;
}

.toggle:hover {
  color: var(--blue);
}
</style>
