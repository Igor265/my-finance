'use client'

import { useState } from 'react'
import { useAccounts } from '@/features/accounts/hooks'
import { useTransactions, useDeleteTransaction } from '@/features/transactions/hooks'
import { useCategories } from '@/features/categories/hooks'
import { TransactionDialog } from '@/features/transactions/components/TransactionDialog'
import type { Transaction } from '@/types/api'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Pencil, Trash2 } from 'lucide-react'

const typeLabel: Record<string, string> = {
  income: 'Receita',
  expense: 'Despesa',
  transfer: 'Transferência',
}

const typeVariant: Record<string, 'default' | 'secondary' | 'outline'> = {
  income: 'default',
  expense: 'secondary',
  transfer: 'outline',
}

export default function TransactionsPage() {
  const [page, setPage] = useState(1)
  const [accountId, setAccountId] = useState('')
  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState<Transaction | undefined>(undefined)

  const { data: accountsData } = useAccounts()
  const { data, isLoading } = useTransactions(accountId, page)
  const { data: categoriesData } = useCategories()
  const { mutate: deleteTransaction } = useDeleteTransaction(accountId)

  const categories = categoriesData?.data ?? []

  function openCreate() {
    setEditing(undefined)
    setDialogOpen(true)
  }

  function openEdit(transaction: Transaction) {
    setEditing(transaction)
    setDialogOpen(true)
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Transações</h1>
        <Button onClick={openCreate} disabled={!accountId}>Nova transação</Button>
      </div>

      <Select
        value={accountId}
        onValueChange={(value) => { setAccountId(value); setPage(1) }}
      >
        <SelectTrigger className="w-64">
          <SelectValue placeholder="Selecione uma conta" />
        </SelectTrigger>
        <SelectContent>
          {accountsData?.data.map((account) => (
            <SelectItem key={account.id} value={account.id}>{account.name}</SelectItem>
          ))}
        </SelectContent>
      </Select>

      {!accountId ? (
        <p className="text-muted-foreground text-sm">Selecione uma conta para ver as transações.</p>
      ) : (
        <>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Descrição</TableHead>
                <TableHead>Tipo</TableHead>
                <TableHead>Valor</TableHead>
                <TableHead>Data</TableHead>
                <TableHead />
              </TableRow>
            </TableHeader>
            <TableBody>
              {isLoading
                ? Array.from({ length: 5 }).map((_, i) => (
                    <TableRow key={i}>
                      <TableCell><Skeleton className="h-4 w-40" /></TableCell>
                      <TableCell><Skeleton className="h-4 w-20" /></TableCell>
                      <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                      <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                      <TableCell />
                    </TableRow>
                  ))
                : data?.data.map((transaction) => (
                    <TableRow key={transaction.id}>
                      <TableCell className="font-medium">{transaction.description}</TableCell>
                      <TableCell>
                        <Badge variant={typeVariant[transaction.type]}>{typeLabel[transaction.type]}</Badge>
                      </TableCell>
                      <TableCell>
                        {new Intl.NumberFormat('pt-BR', {
                          style: 'currency',
                          currency: transaction.currency,
                        }).format(transaction.amount)}
                      </TableCell>
                      <TableCell>
                        {new Date(transaction.date).toLocaleDateString('pt-BR')}
                      </TableCell>
                      <TableCell className="text-right">
                        <Button variant="ghost" size="icon" onClick={() => openEdit(transaction)}>
                          <Pencil className="h-4 w-4" />
                        </Button>
                        <Button variant="ghost" size="icon" onClick={() => deleteTransaction(transaction.id)}>
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
        </>
      )}

      {accountId && (
        <TransactionDialog
          open={dialogOpen}
          onOpenChange={setDialogOpen}
          accountId={accountId}
          transaction={editing}
          categories={categories}
        />
      )}
    </div>
  )
}
