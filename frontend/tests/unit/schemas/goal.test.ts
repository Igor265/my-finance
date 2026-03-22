import { describe, it, expect } from 'vitest'
import { goalSchema } from '@/features/goals/schemas'

const valid = {
  name: 'Viagem para Europa',
  target_amount: 10000,
  current_amount: 2500,
  deadline: '2027-12-31',
}

describe('goalSchema', () => {
  it('should validate correct data', () => {
    const result = goalSchema.safeParse(valid)
    expect(result.success).toBe(true)
  })

  it('should validate with current_amount as zero', () => {
    const result = goalSchema.safeParse({ ...valid, current_amount: 0 })
    expect(result.success).toBe(true)
  })

  it('should reject empty name', () => {
    const result = goalSchema.safeParse({ ...valid, name: '' })
    expect(result.success).toBe(false)
  })

  it('should reject name with only spaces', () => {
    const result = goalSchema.safeParse({ ...valid, name: '     ' })
    expect(result.success).toBe(false)
  })

  it('should reject zero target_amount', () => {
    const result = goalSchema.safeParse({ ...valid, target_amount: 0 })
    expect(result.success).toBe(false)
  })

  it('should reject negative current_amount', () => {
    const result = goalSchema.safeParse({ ...valid, current_amount: -1 })
    expect(result.success).toBe(false)
  })

  it('should reject invalid deadline format', () => {
    const result = goalSchema.safeParse({ ...valid, deadline: '31-12-2027' })
    expect(result.success).toBe(false)
  })

  it('should reject invalid deadline date', () => {
    const result = goalSchema.safeParse({ ...valid, deadline: '2027-13-01' })
    expect(result.success).toBe(false)
  })
})
