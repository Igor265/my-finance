import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { getGoals, createGoal, updateGoal, deleteGoal } from './api'

export function useGoals(page = 1) {
  return useQuery({
    queryKey: ['goals', page],
    queryFn: () => getGoals(page),
  })
}

export function useCreateGoal() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: createGoal,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['goals'] }),
  })
}

export function useUpdateGoal() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }: { id: string; data: Parameters<typeof updateGoal>[1] }) => updateGoal(id, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['goals'] }),
  })
}

export function useDeleteGoal() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: deleteGoal,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['goals'] }),
  })
}
