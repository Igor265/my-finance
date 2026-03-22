import { describe, it, expect } from 'vitest'
import { categorySchema } from '@/features/categories/schemas'

describe('categorySchema', () => {
  it('should validate correct data', () => {
    const result = categorySchema.safeParse({ name: 'Test', type: 'income' })
    expect(result.success).toBe(true)
  })

  it('should reject empty name', () => {
    const result = categorySchema.safeParse({ name: '', type: 'expense' })
    expect(result.success).toBe(false)
  })

  it('should reject name filed with spaces', () => {
    const result = categorySchema.safeParse({ name: '      ', type: 'expense' })
    expect(result.success).toBe(false)
  })

  it('should reject invalid type', () => {
    const result = categorySchema.safeParse({ name: 'Test', type: 'invalid' })
    expect(result.success).toBe(false)
  })
})