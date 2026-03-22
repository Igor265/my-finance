import { describe, it, expect } from 'vitest'
import { budgetSchema } from '@/features/budgets/schemas'

const valid = {
  category_id: 'c1',
  maximum_amount: 500,
  alert_percentage: 80,
  start_date: '2026-03-01',
  end_date: '2026-03-31',
}

describe('budgetSchema', () => {
  it('should validate correct data', () => {
    const result = budgetSchema.safeParse(valid)
    expect(result.success).toBe(true)
  })

  it('should reject empty category_id', () => {
    const result = budgetSchema.safeParse({ ...valid, category_id: '' })
    expect(result.success).toBe(false)
  })

  it('should reject zero maximum_amount', () => {
    const result = budgetSchema.safeParse({ ...valid, maximum_amount: 0 })
    expect(result.success).toBe(false)
  })

  it('should reject alert_percentage below 1', () => {
    const result = budgetSchema.safeParse({ ...valid, alert_percentage: 0 })
    expect(result.success).toBe(false)
  })

  it('should reject alert_percentage above 100', () => {
    const result = budgetSchema.safeParse({ ...valid, alert_percentage: 101 })
    expect(result.success).toBe(false)
  })

  it('should reject invalid start_date format', () => {
    const result = budgetSchema.safeParse({ ...valid, start_date: '01-03-2026' })
    expect(result.success).toBe(false)
  })

  it('should reject invalid end_date format', () => {
    const result = budgetSchema.safeParse({ ...valid, end_date: '31-03-2026' })
    expect(result.success).toBe(false)
  })
})
