'use client'

import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { budgetSchema, type BudgetFormData } from '../schemas'
import { useCreateBudget, useUpdateBudget } from '../hooks'
import type { Budget, Category } from '@/types/api'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

interface Props {
  open: boolean
  onOpenChange: (open: boolean) => void
  budget?: Budget
  categories: Category[]
}

export function BudgetDialog({ open, onOpenChange, budget, categories }: Props) {
  const isEditing = !!budget
  const { mutate: create, isPending: isCreating } = useCreateBudget()
  const { mutate: update, isPending: isUpdating } = useUpdateBudget()

  const form = useForm<BudgetFormData>({
    resolver: zodResolver(budgetSchema),
    defaultValues: { category_id: '', maximum_amount: 0, alert_percentage: 80, start_date: '', end_date: '' },
  })

  useEffect(() => {
    if (budget) {
      form.reset({
        category_id: budget.category_id,
        maximum_amount: budget.maximum_amount,
        alert_percentage: budget.alert_percentage,
        start_date: budget.start_date,
        end_date: budget.end_date,
      })
    } else {
      form.reset({ category_id: '', maximum_amount: 0, alert_percentage: 80, start_date: '', end_date: '' })
    }
  }, [budget, form])

  function onSubmit(data: BudgetFormData) {
    if (isEditing) {
      update({ id: budget.id, data }, { onSuccess: () => onOpenChange(false) })
    } else {
      create(data, { onSuccess: () => onOpenChange(false) })
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{isEditing ? 'Editar orçamento' : 'Novo orçamento'}</DialogTitle>
        </DialogHeader>

        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
            <FormField control={form.control} name="category_id" render={({ field }) => (
              <FormItem>
                <FormLabel>Categoria</FormLabel>
                <Select onValueChange={field.onChange} value={field.value}>
                  <FormControl>
                    <SelectTrigger><SelectValue placeholder="Selecione uma categoria" /></SelectTrigger>
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

            <div className="grid grid-cols-2 gap-4">
              <FormField control={form.control} name="maximum_amount" render={({ field }) => (
                <FormItem>
                  <FormLabel>Valor máximo</FormLabel>
                  <FormControl>
                    <Input type="number" step="0.01" placeholder="0.00" {...field} onChange={(e) => field.onChange(e.target.valueAsNumber)} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )} />

              <FormField control={form.control} name="alert_percentage" render={({ field }) => (
                <FormItem>
                  <FormLabel>Alerta (%)</FormLabel>
                  <FormControl>
                    <Input type="number" min="1" max="100" placeholder="80" {...field} onChange={(e) => field.onChange(e.target.valueAsNumber)} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )} />
            </div>

            <div className="grid grid-cols-2 gap-4">
              <FormField control={form.control} name="start_date" render={({ field }) => (
                <FormItem>
                  <FormLabel>Início</FormLabel>
                  <FormControl><Input type="date" {...field} /></FormControl>
                  <FormMessage />
                </FormItem>
              )} />

              <FormField control={form.control} name="end_date" render={({ field }) => (
                <FormItem>
                  <FormLabel>Fim</FormLabel>
                  <FormControl><Input type="date" {...field} /></FormControl>
                  <FormMessage />
                </FormItem>
              )} />
            </div>

            <div className="flex justify-end gap-2">
              <Button type="button" variant="outline" onClick={() => onOpenChange(false)}>Cancelar</Button>
              <Button type="submit" disabled={isCreating || isUpdating}>{isEditing ? 'Salvar' : 'Criar'}</Button>
            </div>
          </form>
        </Form>
      </DialogContent>
    </Dialog>
  )
}
