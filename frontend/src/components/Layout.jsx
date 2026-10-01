import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import {
  Banknote,
  BookOpen,
  ClipboardList,
  FileText,
  FlaskConical,
  HeartPulse,
  KeyRound,
  LayoutDashboard,
  LogOut,
  Menu,
  MonitorCog,
  Pill,
  Stethoscope,
  Syringe,
  Truck,
  UserRound,
  Users,
} from 'lucide-react'
import { useAuth } from '@/context/AuthContext'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Separator } from '@/components/ui/separator'

function BrandMark({ className = 'size-5' }) {
  return (
    <svg viewBox="0 0 100 100" fill="currentColor" className={className} aria-hidden="true">
      <path d="M40 18h20v22h22v20H60v22H40V60H18V40h22z" />
    </svg>
  )
}

const adminNav = [
  {
    group: 'Overview',
    items: [
      { to: '/admin', label: 'Dashboard', icon: LayoutDashboard, end: true },
    ],
  },
  {
    group: 'Clinical',
    items: [
      { to: '/admin/patients', label: 'Patients', icon: Users },
      { to: '/admin/doctors', label: 'Doctors', icon: Stethoscope },
      { to: '/admin/lab-tests', label: 'Laboratory', icon: FlaskConical },
      { to: '/admin/surgeries', label: 'Surgery', icon: Syringe },
      { to: '/admin/vitals', label: 'Vitals', icon: HeartPulse },
      { to: '/admin/medical-records', label: 'Medical records', icon: FileText },
      { to: '/admin/transfers', label: 'Transfers', icon: Truck },
    ],
  },
  {
    group: 'Pharmacy',
    items: [
      { to: '/admin/prescriptions', label: 'Prescriptions', icon: ClipboardList },
      { to: '/admin/pharmacy', label: 'Medicines & stock', icon: Pill },
    ],
  },
  {
    group: 'Operations',
    items: [
      { to: '/admin/payrolls', label: 'Payroll', icon: Banknote },
      { to: '/admin/accounts', label: 'Accounts', icon: BookOpen },
      { to: '/admin/equipments', label: 'Equipment', icon: MonitorCog },
      { to: '/admin/password-resets', label: 'Password resets', icon: KeyRound },
    ],
  },
]

const doctorNav = [
  {
    group: 'Overview',
    items: [
      { to: '/doctor', label: 'Dashboard', icon: LayoutDashboard, end: true },
    ],
  },
  {
    group: 'Clinical',
    items: [
      { to: '/doctor/patients', label: 'Patients', icon: Users },
      { to: '/doctor/lab-tests', label: 'Laboratory', icon: FlaskConical },
      { to: '/doctor/vitals', label: 'Vitals', icon: HeartPulse },
      { to: '/doctor/transfers', label: 'Transfers', icon: Truck },
    ],
  },
  {
    group: 'Pharmacy',
    items: [
      { to: '/doctor/prescriptions', label: 'Prescriptions', icon: ClipboardList },
    ],
  },
  {
    group: 'Operations',
    items: [
      { to: '/doctor/equipments', label: 'Equipment', icon: MonitorCog },
      { to: '/doctor/payrolls', label: 'Payroll', icon: Banknote },
    ],
  },
]

function NavGroups({ nav }) {
  return (
    <nav className="flex flex-col gap-5 p-3">
      {nav.map((section) => (
        <div key={section.group} className="flex flex-col gap-1">
          <p className="px-2.5 pb-1 text-xs font-medium text-muted-foreground/80">{section.group}</p>
          {section.items.map((item) => (
            <NavLink
              key={item.to}
              to={item.to}
              end={item.end}
              className={({ isActive }) =>
                `flex items-center gap-2.5 rounded-md px-2.5 py-2 text-sm transition-colors ${
                  isActive
                    ? 'bg-sidebar-accent font-medium text-sidebar-accent-foreground'
                    : 'text-muted-foreground hover:bg-accent/60 hover:text-foreground'
                }`
              }
            >
              <item.icon className="size-4 shrink-0" strokeWidth={1.75} aria-hidden="true" />
              {item.label}
            </NavLink>
          ))}
        </div>
      ))}
    </nav>
  )
}

function UserBlock() {
  const { user, logout, isAdmin } = useAuth()
  const navigate = useNavigate()

  const initials = (user?.name || '?')
    .split(' ')
    .map((part) => part[0])
    .filter(Boolean)
    .slice(0, 2)
    .join('')
    .toUpperCase()

  const handleLogout = async () => {
    await logout()
    navigate('/login')
  }

  return (
    <DropdownMenu>
      <DropdownMenuTrigger
        render={
          <button
            type="button"
            className="flex w-full items-center gap-3 rounded-md p-2 text-left transition-colors hover:bg-accent/60"
          />
        }
      >
        <Avatar className="size-8">
          <AvatarFallback className="bg-primary/10 text-xs font-semibold text-primary">{initials}</AvatarFallback>
        </Avatar>
        <span className="min-w-0 flex-1">
          <span className="block truncate text-sm font-medium">{user?.name}</span>
          <span className="block truncate text-xs text-muted-foreground capitalize">{user?.role}</span>
        </span>
      </DropdownMenuTrigger>
      <DropdownMenuContent side="top" align="start" className="w-56">
        <DropdownMenuLabel>{user?.email || user?.number}</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuItem onClick={() => navigate(isAdmin ? '/admin/profile' : '/doctor/profile')}>
          <UserRound className="size-4" />
          My account
        </DropdownMenuItem>
        <DropdownMenuItem onClick={handleLogout}>
          <LogOut className="size-4" />
          Sign out
        </DropdownMenuItem>
      </DropdownMenuContent>
    </DropdownMenu>
  )
}

export default function Layout() {
  const { user, logout, isAdmin } = useAuth()
  const navigate = useNavigate()
  const nav = isAdmin ? adminNav : doctorNav

  const handleLogout = async () => {
    await logout()
    navigate('/login')
  }

  return (
    <div className="min-h-screen">
      {/* Desktop sidebar */}
      <aside className="fixed inset-y-0 left-0 z-20 hidden w-60 flex-col border-r border-sidebar-border bg-sidebar lg:flex">
        <div className="flex h-14 items-center gap-2.5 border-b border-sidebar-border px-4">
          <span className="flex size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
            <BrandMark className="size-4" />
          </span>
          <div className="min-w-0">
            <p className="truncate text-sm font-semibold leading-none">Hospital System</p>
            <p className="mt-1 truncate text-xs capitalize text-muted-foreground">{user?.role} workspace</p>
          </div>
        </div>

        <div className="flex-1 overflow-y-auto">
          <NavGroups nav={nav} />
        </div>

        <div className="border-t border-sidebar-border p-3">
          <UserBlock />
        </div>
      </aside>

      {/* Mobile top bar */}
      <header className="fixed inset-x-0 top-0 z-20 flex h-14 items-center justify-between border-b border-sidebar-border bg-sidebar px-4 lg:hidden">
        <div className="flex items-center gap-2.5">
          <span className="flex size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
            <BrandMark className="size-4" />
          </span>
          <p className="text-sm font-semibold">Hospital System</p>
        </div>
        <DropdownMenu>
          <DropdownMenuTrigger
            render={
              <Button variant="outline" size="icon-sm" aria-label="Open navigation" />
            }
          >
            <Menu className="size-4" />
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" className="w-56">
            {nav.flatMap((section) => [
              <DropdownMenuLabel key={`${section.group}-label`}>{section.group}</DropdownMenuLabel>,
              ...section.items.map((item) => (
                <DropdownMenuItem key={item.to} onClick={() => navigate(item.to)}>
                  <item.icon className="size-4" />
                  {item.label}
                </DropdownMenuItem>
              )),
            ])}
            <DropdownMenuSeparator />
            <DropdownMenuItem onClick={handleLogout}>
              <LogOut className="size-4" />
              Sign out
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </header>

      {/* Content */}
      <main className="px-4 pb-16 pt-20 lg:ml-60 lg:px-8 lg:pt-8">
        <div className="mx-auto max-w-6xl">
          <Outlet />
        </div>
      </main>
    </div>
  )
}
