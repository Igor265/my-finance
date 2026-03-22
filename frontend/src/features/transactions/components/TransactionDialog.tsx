'use client'

import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { transactionSchema, type TransactionFormData } from '../schemas'
import { useCreateTransaction, useUpdateTransaction } from '../hooks'
import type { Transaction, Category } from '@/types/api'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

interface Props {
  open: boolean
  onOpenChange: (open: boolean) => void
  accountId: string
  transaction?: Transaction
  categories: Category[]
}

export function TransactionDialog({ open, onOpenChange, accountId, transaction, categories }: Props) {
  const isEditing = !!transaction
  const { mutate: create, isPending: isCreating } = useCreateTransaction(accountId)
  const { mutate: update, isPending: isUpdating } = useUpdateTransaction(accountId)

  const form = useForm<TransactionFormData>({
    resolver: zodResolver(transactionSchema),
    defaultValues: { description: '', amount: 0, type: 'expense', date: '', category_id: undefined },
  })

  useEffect(() => {
    if (transaction) {
      form.reset({
        description: transaction.description,
        amount: transaction.amount,
        type: transaction.type,
        date: transaction.date,
        category_id: transaction.category_id ?? undefined,
      })
    } else {
      form.reset({ description: '', amount: 0, type: 'expense', date: new
        Date().toISOString().split('T')[0], category_id: undefined })
    }
  }, [transaction, form])

  function onSubmit(data: TransactionFormData) {
    if (isEditing) {
      update({ id: transaction.id, data }, { onSuccess: () => onOpenChange(false) })
    } else {
      create(data, { onSuccess: () => onOpenChange(false) })
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{isEditing ? 'Editar transação' : 'Nova transação'}</DialogTitle>
        </DialogHeader>

        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
            <FormField control={form.control} name="description" render={({ field }) => (
              <FormItem>
                <FormLabel>Descrição</FormLabel>
                <FormControl><Input placeholder="Ex: Supermercado" {...field} /></FormControl>
                <FormMessage />
              </FormItem>
            )} />

            <div className="grid grid-cols-2 gap-4">
              <FormField control={form.control} name="amount" render={({ field }) => (
                <FormItem>
                  <FormLabel>Valor</FormLabel>
                  <FormControl>
                    <Input type="number" step="0.01" placeholder="0.00" {...field} onChange={(e) =>
                      field.onChange(e.target.valueAsNumber)} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )} />

              <FormField control={form.control} name="date" render={({ field }) => (
                <FormItem>
                  <FormLabel>Data</FormLabel>
                  <FormControl><Input type="date" {...field} /></FormControl>
                  <FormMessage />
                </FormItem>
              )} />
            </div>

            <FormField control={form.control} name="type" render={({ field }) => (
              <FormItem>
                <FormLabel>Tipo</FormLabel>
                <Select onValueChange={field.onChange} defaultValue={field.value}>
                  <FormControl>
                    <SelectTrigger><SelectValue placeholder="Selecione o tipo" /></SelectTrigger>
                  </FormControl>
                  <SelectContent>
                    <SelectItem value="income">Receita</SelectItem>
                    <SelectItem value="expense">Despesa</SelectItem>
                    <SelectItem value="transfer">Transferência</SelectItem>
                  </SelectContent>
                </Select>
                <FormMessage />
              </FormItem>
            )} />

            <FormField control={form.control} name="category_id" render={({ field }) => (
              <FormItem>
                <FormLabel>Categoria (opcional)</FormLabel>
                <Select onValueChange={field.onChange} value={field.value ?? ''}>
                  <FormControl>
                    <SelectTrigger><SelectValue placeholder="Selecione uma categoria"
                    /></SelectTrigger>
                  </FormControl>
                  <SelectContent>
                    {categories.map((c) => (
                      <SelectItem key={c.id} value={c.id}>{c.name}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                <FormMessage />
              </FormItem>
            )} />

            <div className="flex justify-end gap-2">
              <Button type="button" variant="outline" onClick={() =>
                onOpenChange(false)}>Cancelar</Button>
              <Button type="submit" disabled={isCreating || isUpdating}>{isEditing ? 'Salvar' :
                'Criar'}</Button>
            </div>
          </form>
        </Form>
      </DialogContent>
    </Dialog>
  )
}