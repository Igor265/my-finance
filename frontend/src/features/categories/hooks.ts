import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import { getCategories, createCategory, updateCategory, deleteCategory } from './api'

export function useCategories(page = 1) {
  return useQuery({
    queryKey: ['categories', page],
    queryFn: () => getCategories(page),
  })
}

export function useCreateCategory() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: createCategory,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['categories'] }),
  })
}

export function useUpdateCategory() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: ({ id, data }: { id: string; data: { name: string; type: string } }) =>
      updateCategory(id, data),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['categories'] }),
  })
}

export function useDeleteCategory() {
  const queryClient = useQueryClient()
  return useMutation({
    mutationFn: deleteCategory,
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ['categories'] }),
  })
}