import { z } from 'zod/v4'

export const loginSchema = z.object({
  email: z.email('E-mail inválido'),
  password: z.string().min(8, { error: 'Senha deve ter no mínimo 8 caracteres' }),
})

export type LoginFormData = z.infer<typeof loginSchema>