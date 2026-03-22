import { describe, it, expect } from 'vitest'
import { accountSchema } from '@/features/accounts/schemas'

describe('accountSchema', () => {
  it('should validate correct data', () => {
    const result = accountSchema.safeParse({ name: 'Nubank', balance: 1000, type: 'checking' })
    expect(result.success).toBe(true)
  })

  it('should reject empty name', () => {
    const result = accountSchema.safeParse({ name: '', balance: 0, type: 'checking' })
    expect(result.success).toBe(false)
  })

  it('should reject name filed with spaces', () => {
    const result = accountSchema.safeParse({ name: '      ', type: 'expense' })
    expect(result.success).toBe(false)
  })

  it('should reject invalid type', () => {
    const result = accountSchema.safeParse({ name: 'Test', balance: 0, type: 'invalid' })
    expect(result.success).toBe(false)
  })
})