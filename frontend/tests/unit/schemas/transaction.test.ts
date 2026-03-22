import { describe, it, expect } from 'vitest'
import {transactionSchema} from "@/features/transactions/schemas";

describe('transactionSchema', () => {
  it('should validate correct data', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', amount: 1000.0, type: 'expense', date: '2026-03-22', category_id: "c1"
    })
    expect(result.success).toBe(true)
  })

  it('should validate correct data without category id', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', amount: 1000.0, type: 'expense', date: '2026-03-22'
    })
    expect(result.success).toBe(true)
  })

  it('should reject empty description', () => {
    const result = transactionSchema.safeParse({
      description: '', amount: 1000.0, type: 'expense', date: '2026-03-22', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })

  it('should reject description filed with spaces', () => {
    const result = transactionSchema.safeParse({
      description: '        ', amount: 1000.0, type: 'expense', date: '2026-03-22', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })

  it('should reject empty amount', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', type: 'expense', date: '2026-03-22', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })

  it('should reject invalid type', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', amount: 1000.0, type: 'invalid', date: '2026-03-22', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })

  it('should reject invalid date format', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', amount: 1000.0, type: 'expense', date: '22-03-2026', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })

  it('should reject invalid date', () => {
    const result = transactionSchema.safeParse({
      description: 'Test', amount: 1000.0, type: 'expense', date: '2026-03-32', category_id: "c1"
    })
    expect(result.success).toBe(false)
  })
})