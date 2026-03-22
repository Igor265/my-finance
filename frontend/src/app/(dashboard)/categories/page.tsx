'use client'

import { useState } from 'react'
import { useCategories, useDeleteCategory } from '@/features/categories/hooks'
import { CategoryDialog } from '@/features/categories/components/CategoryDialog'
import type { Category } from '@/types/api'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Pencil, Trash2 } from 'lucide-react'

const typeLabel: Record<string, string> = {
  income: 'Receita',
  expense: 'Despesa',
}

const typeVariant: Record<string, 'default' | 'secondary'> = {
  income: 'default',
  expense: 'secondary',
}

export default function CategoriesPage() {
  const [page, setPage] = useState(1)
  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState<Category | undefined>(undefined)
  const { data, isLoading } = useCategories(page)
  const { mutate: deleteCategory } = useDeleteCategory()

  function openCreate() {
    setEditing(undefined)
    setDialogOpen(true)
  }

  function openEdit(category: Category) {
    setEditing(category)
    setDialogOpen(true)
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Categorias</h1>
        <Button onClick={openCreate}>Nova categoria</Button>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Nome</TableHead>
            <TableHead>Tipo</TableHead>
            <TableHead />
          </TableRow>
        </TableHeader>
        <TableBody>
          {isLoading
            ? Array.from({ length: 5 }).map((_, i) => (
              <TableRow key={i}>
                <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                <TableCell><Skeleton className="h-4 w-20" /></TableCell>
                <TableCell />
              </TableRow>
            ))
            : data?.data.map((category) => (
              <TableRow key={category.id}>
                <TableCell className="font-medium">{category.name}</TableCell>
                <TableCell>
                  <Badge variant={typeVariant[category.type]}>{typeLabel[category.type]}</Badge>
                </TableCell>
                <TableCell className="text-right">
                  <Button variant="ghost" size="icon" onClick={() => openEdit(category)}>
                    <Pencil className="h-4 w-4" />
                  </Button>
                  <Button variant="ghost" size="icon" onClick={() => deleteCategory(category.id)}>
                    <Trash2 className="h-4 w-4" />
                  </Button>
                </TableCell>
              </TableRow>
            ))}
        </TableBody>
      </Table>

      {data && (
        <div className="flex items-center justify-end gap-2">
          <Button variant="outline" size="sm" disabled={page === 1} onClick={() => setPage((p) => p -
            1)}>
            Anterior
          </Button>
          <span className="text-sm text-muted-foreground">{page} / {data.meta.last_page}</span>
          <Button variant="outline" size="sm" disabled={page === data.meta.last_page} onClick={() =>
            setPage((p) => p + 1)}>
            Próxima
          </Button>
        </div>
      )}

      <CategoryDialog open={dialogOpen} onOpenChange={setDialogOpen} category={editing} />
    </div>
  )
}