import api from '@/lib/api'
import type { Budget, Paginated } from '@/types/api'

export async function getBudgets(page = 1): Promise<Paginated<Budget>> {
  const response = await api.get('/budgets', { params: { page } })
  return response.data
}

export async function createBudget(data: {
  category_id: string
  maximum_amount: number
  alert_percentage: number
  start_date: string
  end_date: string
}): Promise<Budget> {
  const response = await api.post('/budgets', data)
  return response.data.data
}

export async function updateBudget(id: string, data: {
  category_id: string
  maximum_amount: number
  alert_percentage: number
  start_date: string
  end_date: string
}): Promise<Budget> {
  const response = await api.put(`/budgets/${id}`, data)
  return response.data.data
}

export async function deleteBudget(id: string): Promise<void> {
  await api.delete(`/budgets/${id}`)
}
