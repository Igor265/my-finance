import api from '@/lib/api'
import type { Summary, SpendingItem, BudgetStatus, GoalProgress } from '@/types/api'

export async function getSummary(): Promise<Summary> {
  const response = await api.get('/insights/summary')
  return response.data.data
}

export async function getSpending(): Promise<SpendingItem[]> {
  const response = await api.get('/insights/spending')
  return response.data.data
}

export async function getBudgetsStatus(): Promise<BudgetStatus[]> {
  const response = await api.get('/insights/budgets')
  return response.data.data
}

export async function getGoalsProgress(): Promise<GoalProgress[]> {
  const response = await api.get('/insights/goals')
  return response.data.data
}
