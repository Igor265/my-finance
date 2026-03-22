import api from '@/lib/api'
import type { FinancialGoal, Paginated } from '@/types/api'

export async function getGoals(page = 1): Promise<Paginated<FinancialGoal>> {
  const response = await api.get('/goals', { params: { page } })
  return response.data
}

export async function createGoal(data: {
  name: string
  target_amount: number
  current_amount: number
  deadline: string
}): Promise<FinancialGoal> {
  const response = await api.post('/goals', data)
  return response.data.data
}

export async function updateGoal(id: string, data: {
  name: string
  target_amount: number
  current_amount: number
  deadline: string
}): Promise<FinancialGoal> {
  const response = await api.put(`/goals/${id}`, data)
  return response.data.data
}

export async function deleteGoal(id: string): Promise<void> {
  await api.delete(`/goals/${id}`)
}
