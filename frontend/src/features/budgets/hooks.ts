import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { getBudgets, createBudget, updateBudget, deleteBudget } from './api'

export function useBudgets(page = 1) {
  return useQuery({
    queryKey: ['budgets', page],
    queryFn: () => getBudgets(page),
  })
}

export function useCreateBudget() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: createBudget,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['budgets'] }),
  })
}

export function useUpdateBudget() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }: { id: string; data: Parameters<typeof updateBudget>[1] }) => updateBudget(id, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['budgets'] }),
  })
}

export function useDeleteBudget() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: deleteBudget,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['budgets'] }),
  })
}
