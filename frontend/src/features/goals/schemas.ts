import { z } from 'zod/v4'

export const goalSchema = z.object({
  name: z.string().trim().min(1, { error: 'Nome é obrigatório' }),
  target_amount: z.number({ error: 'Valor alvo é obrigatório' }).positive({ message: 'Deve ser positivo' }),
  current_amount: z.number({ error: 'Valor atual é obrigatório' }).min(0),
  deadline: z.string()
    .trim()
    .min(1, { message: 'Prazo é obrigatório' })
    .regex(/^\d{4}-\d{2}-\d{2}$/, {
      message: 'Use o formato YYYY-MM-DD',
    })
    .refine((val) => {
      const date = new Date(val);
      return !isNaN(date.getTime());
    }, {
      message: 'Data inválida',
    })
})

export type GoalFormData = z.infer<typeof goalSchema>