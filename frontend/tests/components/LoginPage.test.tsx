import { describe, it, expect, vi } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'

vi.mock('next/navigation', () => ({
  useRouter: () => ({ push: vi.fn() }),
  usePathname: () => '/',
}))
import userEvent from '@testing-library/user-event'
import { http, HttpResponse } from 'msw'
import { server } from '../mocks/server'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import LoginPage from '@/app/(auth)/login/page'

function wrapper({ children }: { children: React.ReactNode }) {
  const queryClient = new QueryClient({ defaultOptions: { queries: { retry: false } } })
  return <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
}

describe('LoginPage', () => {
  it('should render email and password fields', () => {
    render(<LoginPage />, { wrapper })

    expect(screen.getByLabelText('E-mail')).toBeInTheDocument()
    expect(screen.getByLabelText('Senha')).toBeInTheDocument()
    expect(screen.getByRole('button', { name: 'Entrar' })).toBeInTheDocument()
  })

  it('should show validation errors on empty submit', async () => {
    render(<LoginPage />, { wrapper })

    await userEvent.click(screen.getByRole('button', { name: 'Entrar' }))

    await waitFor(() => {
      expect(screen.getByText('E-mail inválido')).toBeInTheDocument()
    })
  })

  it('should call login API with correct data', async () => {
    let requestBody: unknown

    server.use(
      http.post('http://localhost:8080/api/v1/auth/login', async ({ request }) => {
        requestBody = await request.json()
        return HttpResponse.json({
          token: 'fake-token', user: { id: 1, name: 'Test', email: 'test@test.com' }
        })
      }),
    )

    render(<LoginPage />, { wrapper })

    await userEvent.type(screen.getByLabelText('E-mail'), 'test@test.com')
    await userEvent.type(screen.getByLabelText('Senha'), 'password123')
    await userEvent.click(screen.getByRole('button', { name: 'Entrar' }))

    await waitFor(() => {
      expect(requestBody).toEqual({ email: 'test@test.com', password: 'password123' })
    })
  })
})