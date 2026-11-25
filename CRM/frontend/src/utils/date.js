// "So'ngi faollik" ustuni uchun
export function timeAgo(value) {
  if (!value) return '-'

  const date = new Date(value)
  const minutes = Math.floor((Date.now() - date.getTime()) / 60000)

  if (minutes < 1) return 'hozirgina'
  if (minutes < 60) return `${minutes} minut oldin`

  const hours = Math.floor(minutes / 60)
  if (hours < 24) return `${hours} soat oldin`

  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  })
}
