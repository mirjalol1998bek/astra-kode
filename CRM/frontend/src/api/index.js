import axios from 'axios'
import router from '../router'
import { auth } from '../store/auth'

const api = axios.create({
  baseURL: '/api',
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const url = error.config?.url
    // sessiya tugagan bo'lsa login sahifasiga qaytaramiz
    if (error.response?.status === 401 && url !== '/login' && url !== '/me') {
      auth.user = null
      router.push('/login')
    }
    return Promise.reject(error)
  },
)

export default api
