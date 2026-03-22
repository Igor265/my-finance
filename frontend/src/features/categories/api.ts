import api from '@/lib/api'
import type { Category, Paginated } from '@/types/api'

export async function getCategories(page = 1): Promise<Paginated<Category>> {
  const response = await api.get('/categories', { params: { page } })
  return response.data
}

export async function createCategory(data: { name: string; type: string }): Promise<Category> {
  const response = await api.post('/categories', data)
  return response.data.data
}

export async function updateCategory(id: string, data: { name: string; type: string }):
  Promise<Category> {
  const response = await api.put(`/categories/${id}`, data)
  return response.data.data
}

export async function deleteCategory(id: string): Promise<void> {
  await api.delete(`/categories/${id}`)
}