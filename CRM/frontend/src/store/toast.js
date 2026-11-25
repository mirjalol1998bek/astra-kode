import { reactive } from 'vue'

export const toast = reactive({
  text: '',
  type: 'success',
})

let timer = null

export function showToast(text, type = 'success') {
  toast.text = text
  toast.type = type

  clearTimeout(timer)
  timer = setTimeout(() => {
    toast.text = ''
  }, 2500)
}
