import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { getTransactions, createTransaction, updateTransaction, deleteTransaction } from './api'

export function useTransactions(accountId: string, page = 1) {
  return useQuery({
    queryKey: ['transactions', accountId, page],
    queryFn: () => getTransactions(accountId, page),
    enabled: !!accountId,
  })
}

export function useCreateTransaction(accountId: string) {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (data: { description: string; amount: number; type: string; date: string; category_id?:
        string }) => createTransaction(accountId, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['transactions', accountId] }),
  })
}

export function useUpdateTransaction(accountId: string) {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }: { id: string; data: { description: string; amount: number; type: string;
        date: string; category_id?: string } }) => updateTransaction(accountId, id, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['transactions', accountId] }),
  })
}

export function useDeleteTransaction(accountId: string) {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: (id: string) => deleteTransaction(accountId, id),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['transactions', accountId] }),
  })
}