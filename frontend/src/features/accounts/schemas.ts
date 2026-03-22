import { z } from 'zod/v4'

export const accountSchema = z.object({
  name: z.string().trim().min(1, { error: 'Nome é obrigatório' }),
  balance: z.number({ error: 'Saldo é obrigatório' }),
  type: z.enum(['checking', 'savings', 'wallet'], { error: 'Tipo inválido' }),
})

export type AccountFormData = z.infer<typeof accountSchema>