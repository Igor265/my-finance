import { z } from 'zod/v4'

export const budgetSchema = z.object({
  category_id: z.string().trim().min(1, { error: 'Categoria é obrigatória' }),
  maximum_amount: z.number({ error: 'Valor máximo é obrigatório' }).positive({ message: 'Deve ser positivo' }),
  alert_percentage: z.number({ error: 'Percentual é obrigatório' }).min(1).max(100),
  start_date: z.string()
    .trim()
    .min(1, { message: 'Data de início é obrigatória' })
    .regex(/^\d{4}-\d{2}-\d{2}$/, {
      message: 'Use o formato YYYY-MM-DD',
    })
    .refine((val) => {
      const date = new Date(val);
      return !isNaN(date.getTime());
    }, {
      message: 'Data inválida',
    }),
  end_date: z.string()
    .trim()
    .min(1, { message: 'Data de fim é obrigatória' })
    .regex(/^\d{4}-\d{2}-\d{2}$/, {
      message: 'Use o formato YYYY-MM-DD',
    })
    .refine((val) => {
      const date = new Date(val);
      return !isNaN(date.getTime());
    }, {
      message: 'Data inválida',
    }),
})

export type BudgetFormData = z.infer<typeof budgetSchema>