'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarHeader,
  SidebarFooter,
} from '@/components/ui/sidebar'
import {
  LayoutDashboard,
  Wallet,
  Tags,
  ArrowLeftRight,
  PiggyBank,
  Target,
  LogOut,
} from 'lucide-react'
import { useLogout } from '@/features/auth/hooks'

const navItems = [
  { href: '/', label: 'Dashboard', icon: LayoutDashboard },
  { href: '/accounts', label: 'Contas', icon: Wallet },
  { href: '/categories', label: 'Categorias', icon: Tags },
  { href: '/transactions', label: 'Transações', icon: ArrowLeftRight },
  { href: '/budgets', label: 'Orçamentos', icon: PiggyBank },
  { href: '/goals', label: 'Metas', icon: Target },
]

export function AppSidebar() {
  const pathname = usePathname()
  const { mutate: logout } = useLogout()

  return (
    <Sidebar>
      <SidebarHeader className="p-4">
        <span className="text-xl font-bold">MyFinance</span>
      </SidebarHeader>

      <SidebarContent>
        <SidebarGroup>
          <SidebarGroupLabel>Menu</SidebarGroupLabel>
          <SidebarMenu>
            {navItems.map((item) => (
              <SidebarMenuItem key={item.href}>
                <SidebarMenuButton asChild isActive={pathname === item.href}>
                  <Link href={item.href}>
                    <item.icon />
                    <span>{item.label}</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            ))}
          </SidebarMenu>
        </SidebarGroup>
      </SidebarContent>

      <SidebarFooter>
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton onClick={() => logout()}>
              <LogOut />
              <span>Sair</span>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarFooter>
    </Sidebar>
  )
}