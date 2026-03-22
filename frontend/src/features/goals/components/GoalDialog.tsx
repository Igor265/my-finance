'use client'

import { useEffect } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { goalSchema, type GoalFormData } from '../schemas'
import { useCreateGoal, useUpdateGoal } from '../hooks'
import type { FinancialGoal } from '@/types/api'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

interface Props {
  open: boolean
  onOpenChange: (open: boolean) => void
  goal?: FinancialGoal
}

export function GoalDialog({ open, onOpenChange, goal }: Props) {
  const isEditing = !!goal
  const { mutate: create, isPending: isCreating } = useCreateGoal()
  const { mutate: update, isPending: isUpdating } = useUpdateGoal()

  const form = useForm<GoalFormData>({
    resolver: zodResolver(goalSchema),
    defaultValues: { name: '', target_amount: 0, current_amount: 0, deadline: '' },
  })

  useEffect(() => {
    if (goal) {
      form.reset({
        name: goal.name,
        target_amount: goal.target_amount,
        current_amount: goal.current_amount,
        deadline: goal.deadline,
      })
    } else {
      form.reset({ name: '', target_amount: 0, current_amount: 0, deadline: '' })
    }
  }, [goal, form])

  function onSubmit(data: GoalFormData) {
    if (isEditing) {
      update({ id: goal.id, data }, { onSuccess: () => onOpenChange(false) })
    } else {
      create(data, { onSuccess: () => onOpenChange(false) })
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>{isEditing ? 'Editar meta' : 'Nova meta'}</DialogTitle>
        </DialogHeader>

        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
            <FormField control={form.control} name="name" render={({ field }) => (
              <FormItem>
                <FormLabel>Nome</FormLabel>
                <FormControl><Input placeholder="Ex: Viagem para Europa" {...field} /></FormControl>
                <FormMessage />
              </FormItem>
            )} />

            <div className="grid grid-cols-2 gap-4">
              <FormField control={form.control} name="target_amount" render={({ field }) => (
                <FormItem>
                  <FormLabel>Valor alvo</FormLabel>
                  <FormControl>
                    <Input type="number" step="0.01" placeholder="0.00" {...field} onChange={(e) => field.onChange(e.target.valueAsNumber)} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )} />

              <FormField control={form.control} name="current_amount" render={({ field }) => (
                <FormItem>
                  <FormLabel>Valor atual</FormLabel>
                  <FormControl>
                    <Input type="number" step="0.01" placeholder="0.00" {...field} onChange={(e) => field.onChange(e.target.valueAsNumber)} />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )} />
            </div>

            <FormField control={form.control} name="deadline" render={({ field }) => (
              <FormItem>
                <FormLabel>Prazo</FormLabel>
                <FormControl><Input type="date" {...field} /></FormControl>
                <FormMessage />
              </FormItem>
            )} />

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
