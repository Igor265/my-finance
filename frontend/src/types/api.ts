export interface PaginationLinks {
  first: string
  last: string
  prev: string | null
  next: string | null
}

export interface PaginationMeta {
  current_page: number
  from: number | null
  last_page: number
  per_page: number
  to: number | null
  total: number
}

export interface Paginated<T> {
  data: T[]
  links: PaginationLinks
  meta: PaginationMeta
}

export interface User {
  id: number
  name: string
  email: string
}

export interface AuthResponse {
  token: string
  user: User
}

export interface Account {
  id: string
  name: string
  balance: number
  currency: string
  type: 'checking' | 'savings' | 'wallet'
}

export interface Category {
  id: string
  name: string
  type: 'income' | 'expense'
}

export interface Transaction {
  id: string
  account_id: string
  category_id: string | null
  description: string
  amount: number
  currency: string
  type: 'income' | 'expense' | 'transfer'
  date: string
}

export interface Budget {
  id: string
  category_id: string
  maximum_amount: number
  alert_percentage: number
  currency: string
  start_date: string
  end_date: string
}

export interface FinancialGoal {
  id: string
  name: string
  target_amount: number
  current_amount: number
  currency: string
  deadline: string
}

export interface Summary {
  total_balance: number
  currency: string
  account_count: number
}

export interface SpendingItem {
  category_id: string | null
  amount: number
  currency: string
  count: number
}

export interface BudgetStatus {
  budget_id: string
  category_id: string
  maximum_amount: number
  spent_amount: number
  percentage_used: number
  currency: string
  start_date: string
  end_date: string
}

export interface GoalProgress {
  goal_id: string
  name: string
  target_amount: number
  current_amount: number
  percentage: number
  currency: string
  deadline: string
}