'use client'

import { useState } from 'react'
import { useGoals, useDeleteGoal } from '@/features/goals/hooks'
import { GoalDialog } from '@/features/goals/components/GoalDialog'
import type { FinancialGoal } from '@/types/api'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Pencil, Trash2 } from 'lucide-react'

export default function GoalsPage() {
  const [page, setPage] = useState(1)
  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState<FinancialGoal | undefined>(undefined)
  const { data, isLoading } = useGoals(page)
  const { mutate: deleteGoal } = useDeleteGoal()

  function openCreate() {
    setEditing(undefined)
    setDialogOpen(true)
  }

  function openEdit(goal: FinancialGoal) {
    setEditing(goal)
    setDialogOpen(true)
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Metas</h1>
        <Button onClick={openCreate}>Nova meta</Button>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Nome</TableHead>
            <TableHead>Progresso</TableHead>
            <TableHead>Alvo</TableHead>
            <TableHead>Prazo</TableHead>
            <TableHead />
          </TableRow>
        </TableHeader>
        <TableBody>
          {isLoading
            ? Array.from({ length: 5 }).map((_, i) => (
                <TableRow key={i}>
                  <TableCell><Skeleton className="h-4 w-40" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell />
                </TableRow>
              ))
            : data?.data.map((goal) => {
                const percentage = goal.target_amount > 0
                  ? Math.min(100, Math.round((goal.current_amount / goal.target_amount) * 100))
                  : 0
                return (
                  <TableRow key={goal.id}>
                    <TableCell className="font-medium">{goal.name}</TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <div className="h-2 w-24 rounded-full bg-muted overflow-hidden">
                          <div className="h-full bg-primary rounded-full" style={{ width: `${percentage}%` }} />
                        </div>
                        <span className="text-sm text-muted-foreground">{percentage}%</span>
                      </div>
                    </TableCell>
                    <TableCell>
                      {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: goal.currency }).format(goal.target_amount)}
                    </TableCell>
                    <TableCell className="text-sm text-muted-foreground">
                      {new Date(goal.deadline).toLocaleDateString('pt-BR')}
                    </TableCell>
                    <TableCell className="text-right">
                      <Button variant="ghost" size="icon" onClick={() => openEdit(goal)}>
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button variant="ghost" size="icon" onClick={() => deleteGoal(goal.id)}>
                        <Trash2 className="h-4 w-4" />
                      </Button>
                    </TableCell>
                  </TableRow>
                )
              })}
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

      <GoalDialog open={dialogOpen} onOpenChange={setDialogOpen} goal={editing} />
    </div>
  )
}
