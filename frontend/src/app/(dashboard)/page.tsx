'use client'

import { useSummary, useSpending, useBudgetsStatus, useGoalsProgress } from '@/features/insights/hooks'
import { useCategories } from '@/features/categories/hooks'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import { Wallet, TrendingDown, Target, PiggyBank } from 'lucide-react'
import { PieChart, Pie, Cell, Tooltip, ResponsiveContainer } from 'recharts'

const COLORS = ['#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe']

export default function DashboardPage() {
  const { data: summary, isLoading: loadingSummary } = useSummary()
  const { data: spending, isLoading: loadingSpending } = useSpending()
  const { data: budgets, isLoading: loadingBudgets } = useBudgetsStatus()
  const { data: goals, isLoading: loadingGoals } = useGoalsProgress()
  const { data: categoriesData } = useCategories()

  const categories = categoriesData?.data ?? []
  const categoryName = (id: string | null) =>
    id ? (categories.find((c) => c.id === id)?.name ?? 'Sem categoria') : 'Sem categoria'

  const currency = summary?.currency ?? 'BRL'
  const fmt = (value: number) =>
    new Intl.NumberFormat('pt-BR', { style: 'currency', currency }).format(value)

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-semibold">Dashboard</h1>

      {/* Summary */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Saldo total</CardTitle>
            <Wallet className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            {loadingSummary
              ? <Skeleton className="h-8 w-32" />
              : <p className="text-2xl font-bold">{fmt(summary?.total_balance ?? 0)}</p>}
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">Contas</CardTitle>
            <PiggyBank className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            {loadingSummary
              ? <Skeleton className="h-8 w-16" />
              : <p className="text-2xl font-bold">{summary?.account_count ?? 0}</p>}
          </CardContent>
        </Card>
      </div>

      {/* Spending + Budgets */}
      <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2 text-base">
              <TrendingDown className="h-4 w-4" /> Gastos do mês
            </CardTitle>
          </CardHeader>
          <CardContent>
            {loadingSpending ? (
              <Skeleton className="h-48 w-full" />
            ) : !spending?.length ? (
              <p className="text-sm text-muted-foreground">Nenhum gasto registrado.</p>
            ) : (
              <div className="flex items-center gap-4">
                <ResponsiveContainer width={160} height={160}>
                  <PieChart>
                    <Pie data={spending} dataKey="amount" cx="50%" cy="50%" outerRadius={70}>
                      {spending.map((_, i) => (
                        <Cell key={i} fill={COLORS[i % COLORS.length]} />
                      ))}
                    </Pie>
                    <Tooltip formatter={(v) => fmt(Number(v))} />
                  </PieChart>
                </ResponsiveContainer>
                <ul className="space-y-1 text-sm">
                  {spending.map((item, i) => (
                    <li key={i} className="flex items-center gap-2">
                      <span className="h-2 w-2 rounded-full" style={{ background: COLORS[i % COLORS.length] }} />
                      <span className="text-muted-foreground">{categoryName(item.category_id)}</span>
                      <span className="font-medium">{fmt(item.amount)}</span>
                    </li>
                  ))}
                </ul>
              </div>
            )}
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2 text-base">
              <PiggyBank className="h-4 w-4" /> Orçamentos
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            {loadingBudgets ? (
              <Skeleton className="h-24 w-full" />
            ) : !budgets?.length ? (
              <p className="text-sm text-muted-foreground">Nenhum orçamento cadastrado.</p>
            ) : (
              budgets.map((b) => (
                <div key={b.budget_id} className="space-y-1">
                  <div className="flex justify-between text-sm">
                    <span>{categoryName(b.category_id)}</span>
                    <span className={b.percentage_used >= 100 ? 'text-destructive font-medium' : 'text-muted-foreground'}>
                      {fmt(b.spent_amount)} / {fmt(b.maximum_amount)}
                    </span>
                  </div>
                  <div className="h-2 w-full rounded-full bg-muted overflow-hidden">
                    <div
                      className={`h-full rounded-full transition-all ${b.percentage_used >= 100 ? 'bg-destructive' : 'bg-primary'}`}
                      style={{ width: `${Math.min(100, b.percentage_used)}%` }}
                    />
                  </div>
                </div>
              ))
            )}
          </CardContent>
        </Card>
      </div>

      {/* Goals */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2 text-base">
            <Target className="h-4 w-4" /> Metas financeiras
          </CardTitle>
        </CardHeader>
        <CardContent className="space-y-3">
          {loadingGoals ? (
            <Skeleton className="h-24 w-full" />
          ) : !goals?.length ? (
            <p className="text-sm text-muted-foreground">Nenhuma meta cadastrada.</p>
          ) : (
            goals.map((g) => (
              <div key={g.goal_id} className="space-y-1">
                <div className="flex justify-between text-sm">
                  <span className="font-medium">{g.name}</span>
                  <span className="text-muted-foreground">{g.percentage.toFixed(0)}%</span>
                </div>
                <div className="h-2 w-full rounded-full bg-muted overflow-hidden">
                  <div
                    className="h-full bg-primary rounded-full transition-all"
                    style={{ width: `${Math.min(100, g.percentage)}%` }}
                  />
                </div>
                <div className="flex justify-between text-xs text-muted-foreground">
                  <span>{fmt(g.current_amount)}</span>
                  <span>{fmt(g.target_amount)}</span>
                </div>
              </div>
            ))
          )}
        </CardContent>
      </Card>
    </div>
  )
}
