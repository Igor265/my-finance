'use client'

import { useState } from 'react'
import { useBudgets, useDeleteBudget } from '@/features/budgets/hooks'
import { BudgetDialog } from '@/features/budgets/components/BudgetDialog'
import { useCategories } from '@/features/categories/hooks'
import type { Budget } from '@/types/api'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Pencil, Trash2 } from 'lucide-react'

export default function BudgetsPage() {
  const [page, setPage] = useState(1)
  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState<Budget | undefined>(undefined)
  const { data, isLoading } = useBudgets(page)
  const { data: categoriesData } = useCategories()
  const { mutate: deleteBudget } = useDeleteBudget()

  const categories = categoriesData?.data ?? []

  function openCreate() {
    setEditing(undefined)
    setDialogOpen(true)
  }

  function openEdit(budget: Budget) {
    setEditing(budget)
    setDialogOpen(true)
  }

  const categoryName = (id: string) => categories.find((c) => c.id === id)?.name ?? id

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Orçamentos</h1>
        <Button onClick={openCreate}>Novo orçamento</Button>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Categoria</TableHead>
            <TableHead>Limite</TableHead>
            <TableHead>Alerta</TableHead>
            <TableHead>Período</TableHead>
            <TableHead />
          </TableRow>
        </TableHeader>
        <TableBody>
          {isLoading
            ? Array.from({ length: 5 }).map((_, i) => (
                <TableRow key={i}>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-16" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell />
                </TableRow>
              ))
            : data?.data.map((budget) => (
                <TableRow key={budget.id}>
                  <TableCell className="font-medium">{categoryName(budget.category_id)}</TableCell>
                  <TableCell>
                    {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: budget.currency }).format(budget.maximum_amount)}
                  </TableCell>
                  <TableCell>{budget.alert_percentage}%</TableCell>
                  <TableCell className="text-sm text-muted-foreground">
                    {new Date(budget.start_date).toLocaleDateString('pt-BR')} —{' '}
                    {new Date(budget.end_date).toLocaleDateString('pt-BR')}
                  </TableCell>
                  <TableCell className="text-right">
                    <Button variant="ghost" size="icon" onClick={() => openEdit(budget)}>
                      <Pencil className="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="icon" onClick={() => deleteBudget(budget.id)}>
                      <Trash2 className="h-4 w-4" />
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
        </TableBody>
      </Table>

      {data && (
        <div className="flex items-center justify-end gap-2">
          <Button variant="outline" size="sm" disabled={page === 1} onClick={() => setPage((p) => p - 1)}>
            Anterior
          </Button>
          <span className="text-sm text-muted-foreground">{page} / {data.meta.last_page}</span>
          <Button variant="outline" size="sm" disabled={page === data.meta.last_page} onClick={() => setPage((p) => p + 1)}>
            Próxima
          </Button>
        </div>
      )}

      <BudgetDialog open={dialogOpen} onOpenChange={setDialogOpen} budget={editing} categories={categories} />
    </div>
  )
}
