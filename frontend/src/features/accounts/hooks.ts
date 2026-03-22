import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { getAccounts, createAccount, updateAccount, deleteAccount } from './api'

export function useAccounts(page = 1) {
  return useQuery({
    queryKey: ['accounts', page],
    queryFn: () => getAccounts(page),
  })
}

export function useCreateAccount() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: createAccount,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['accounts'] }),
  })
}

export function useUpdateAccount() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }: { id: string; data: { name: string; balance: number; type: string } }) => updateAccount(id, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['accounts'] }),
  })
}

export function useDeleteAccount() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: deleteAccount,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['accounts'] }),
  })
}