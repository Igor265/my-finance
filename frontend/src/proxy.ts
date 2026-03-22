export function proxy(request: Request) {
  const url = new URL(request.url)
  const isLoginPage = url.pathname === '/login'

  const cookieHeader = request.headers.get('cookie') ?? ''
  const token = cookieHeader
    .split(';')
    .find((c) => c.trim().startsWith('auth_token='))
    ?.split('=')[1]

  if (!token && !isLoginPage) {
    return Response.redirect(new URL('/login', request.url))
  }

  if (token && isLoginPage) {
    return Response.redirect(new URL('/', request.url))
  }
}

export const config = {
  matcher: ['/((?!api|_next/static|_next/image|favicon.ico).*)'],
}