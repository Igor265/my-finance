import { z } from 'zod/v4'

export const transactionSchema = z.object({
  description: z.string().trim().min(1, { error: 'Descrição é obrigatória' }),
  amount: z.number({ error: 'Valor é obrigatório' }).positive({ message: 'Valor deve ser positivo' }),
  type: z.enum(['income', 'expense', 'transfer'], { error: 'Tipo inválido' }),
  date: z.string()
    .trim()
    .min(1, { message: 'Data é obrigatória' })
    .regex(/^\d{4}-\d{2}-\d{2}$/, {
      message: 'Use o formato YYYY-MM-DD',
    })
    .refine((val) => {
      const date = new Date(val);
      return !isNaN(date.getTime());
    }, {
      message: 'Data inválida',
    }),
  category_id: z.string().optional(),
})

export type TransactionFormData = z.infer<typeof transactionSchema>