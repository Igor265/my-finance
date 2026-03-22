import { z } from 'zod/v4'

export const categorySchema = z.object({
  name: z.string().trim().min(1, { error: 'Nome é obrigatório' }),
  type: z.enum(['income', 'expense'], { error: 'Tipo inválido' }),
})

export type CategoryFormData = z.infer<typeof categorySchema>