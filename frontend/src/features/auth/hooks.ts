import { useMutation } from '@tanstack/react-query'
import { useRouter } from 'next/navigation'
import { login, logout } from './api'

export function useLogin() {
  const router = useRouter()

  return useMutation({
    mutationFn: ({ email, password }: { email: string; password: string }) =>
      login(email, password),
    onSuccess: (data) => {
      localStorage.setItem('auth_token', data.token)
      document.cookie = `auth_token=${data.token}; path=/`
      router.push('/')
    },
  })
}

export function useLogout() {
  const router = useRouter()

  return useMutation({
    mutationFn: logout,
    onSuccess: () => {
      localStorage.removeItem('auth_token')
      document.cookie = 'auth_token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT'
      router.push('/login')
    },
  })
}