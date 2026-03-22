'use client'

import { useState } from 'react'
import { useAccounts, useDeleteAccount } from '@/features/accounts/hooks'
import { AccountDialog } from '@/features/accounts/components/AccountDialog'
import type { Account } from '@/types/api'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Pencil, Trash2 } from 'lucide-react'

const typeLabel: Record<string, string> = {
  checking: 'Corrente',
  savings: 'Poupança',
  wallet: 'Carteira',
}

export default function AccountsPage() {
  const [page, setPage] = useState(1)
  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState<Account | undefined>(undefined)
  const { data, isLoading } = useAccounts(page)
  const { mutate: deleteAccount } = useDeleteAccount()

  function openCreate() {
    setEditing(undefined)
    setDialogOpen(true)
  }

  function openEdit(account: Account) {
    setEditing(account)
    setDialogOpen(true)
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold">Contas</h1>
        <Button onClick={openCreate}>Nova conta</Button>
      </div>

      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Nome</TableHead>
            <TableHead>Tipo</TableHead>
            <TableHead>Saldo</TableHead>
            <TableHead />
          </TableRow>
        </TableHeader>
        <TableBody>
          {isLoading
            ? Array.from({ length: 5 }).map((_, i) => (
                <TableRow key={i}>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-20" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell />
                </TableRow>
              ))
            : data?.data.map((account) => (
                <TableRow key={account.id}>
                  <TableCell className="font-medium">{account.name}</TableCell>
                  <TableCell>
                    <Badge variant="secondary">{typeLabel[account.type]}</Badge>
                  </TableCell>
                  <TableCell>
                    {new Intl.NumberFormat('pt-BR', {
                      style: 'currency',
                      currency: account.currency,
                    }).format(account.balance)}
                  </TableCell>
                  <TableCell className="text-right">
                    <Button variant="ghost" size="icon" onClick={() => openEdit(account)}>
                      <Pencil className="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="icon" onClick={() => deleteAccount(account.id)}>
                      <Trash2 className="h-4 w-4" />
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
        </TableBody>
      </Table>

      {data && (
        <div className="flex items-center justify-end gap-2">
          <Button
            variant="outline"
            size="sm"
            disabled={page === 1}
            onClick={() => setPage((p) => p - 1)}
          >
            Anterior
          </Button>
          <span className="text-sm text-muted-foreground">
            {page} / {data.meta.last_page}
          </span>
          <Button
            variant="outline"
            size="sm"
            disabled={page === data.meta.last_page}
            onClick={() => setPage((p) => p + 1)}
          >
            Próxima
          </Button>
        </div>
      )}

      <AccountDialog open={dialogOpen} onOpenChange={setDialogOpen} account={editing} />
    </div>
  )
}
