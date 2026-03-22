import api from '@/lib/api'
import type { Account, Paginated } from '@/types/api'

export async function getAccounts(page = 1): Promise<Paginated<Account>> {
  const response = await api.get('/accounts', { params: { page } })
  return response.data
}

export async function createAccount(data: { name: string; balance: number; type: string }):
  Promise<Account> {
  const response = await api.post('/accounts', data)
  return response.data.data
}

export async function updateAccount(id: string, data: { name: string; balance: number; type: string }):
  Promise<Account> {
  const response = await api.put(`/accounts/${id}`, data)
  return response.data.data
}

export async function deleteAccount(id: string): Promise<void> {
  await api.delete(`/accounts/${id}`)
}