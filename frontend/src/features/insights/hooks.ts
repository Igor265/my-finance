import { useQuery } from '@tanstack/react-query'
import { getSummary, getSpending, getBudgetsStatus, getGoalsProgress } from './api'

export function useSummary() {
  return useQuery({
    queryKey: ['insights', 'summary'],
    queryFn: getSummary,
  })
}

export function useSpending() {
  return useQuery({
    queryKey: ['insights', 'spending'],
    queryFn: getSpending,
  })
}

export function useBudgetsStatus() {
  return useQuery({
    queryKey: ['insights', 'budgets'],
    queryFn: getBudgetsStatus,
  })
}

export function useGoalsProgress() {
  return useQuery({
    queryKey: ['insights', 'goals'],
    queryFn: getGoalsProgress,
  })
}
