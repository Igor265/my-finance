import api from '@/lib/api'
import type { Transaction, Paginated } from '@/types/api'

export async function getTransactions(accountId: string, page = 1): Promise<Paginated<Transaction>> {
  const response = await api.get(`/accounts/${accountId}/transactions`, { params: { page } })
  return response.data
}

export async function createTransaction(
  accountId: string,
  data: { description: string; amount: number; type: string; date: string; category_id?: string },
): Promise<Transaction> {
  const response = await api.post(`/accounts/${accountId}/transactions`, data)
  return response.data.data
}

export async function updateTransaction(
  accountId: string,
  id: string,
  data: { description: string; amount: number; type: string; date: string; category_id?: string },
): Promise<Transaction> {
  const response = await api.put(`/accounts/${accountId}/transactions/${id}`, data)
  return response.data.data
}

export async function deleteTransaction(accountId: string, id: string): Promise<void> {
  await api.delete(`/accounts/${accountId}/transactions/${id}`)
}