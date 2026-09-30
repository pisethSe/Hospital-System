import { Link } from 'react-router-dom'
import {
  ClipboardList,
  FlaskConical,
  HeartPulse,
  Pill,
  Stethoscope,
  Syringe,
  Users,
} from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Separator } from '@/components/ui/separator'
import { RecordCode } from '@/components/ui'

const features = [
  {
    icon: Users,
    title: 'Patients',
    module: 'Front desk',
    description:
      'Register patients in seconds. Every patient gets a record number automatically, and discharge records keep themselves up to date.',
  },
  {
    icon: Stethoscope,
    title: 'Doctors',
    module: 'Administration',
    description:
      'Doctor accounts with their own sign-in ID and department, so each doctor only sees the work that is theirs.',
  },
  {
    icon: FlaskConical,
    title: 'Laboratory',
    module: 'Lab',
    description:
      'Request tests, record results and see at a glance which panels are still waiting on the bench.',
  },
  {
    icon: Syringe,
    title: 'Surgery',
    module: 'Theatre',
    description:
      'Schedule theatre patients, assign the surgeon and track each operation from pending to done.',
  },
  {
    icon: ClipboardList,
    title: 'Prescriptions',
    module: 'Ward rounds',
    description:
      'Write a prescription with medicines, quantities and dosage times — stored with the patient record.',
  },
  {
    icon: Pill,
    title: 'Pharmacy',
    module: 'Stock room',
    description:
      'Keep medicines, categories, vendors and quantities in one inventory, with barcodes generated for you.',
  },
]

function VitalsStrip() {
  return (
    <div className="grid grid-cols-4 divide-x divide-border rounded-lg border border-border bg-card">
      {[
        ['Temp', '36.8 °C'],
        ['Pulse', '72 bpm'],
        ['Resp', '16 rpm'],
        ['BP', '120/80'],
      ].map(([label, value]) => (
        <div key={label} className="px-3 py-2.5">
          <p className="text-[11px] font-medium text-muted-foreground">{label}</p>
          <p className="mt-0.5 text-sm font-semibold tabular-nums text-foreground">{value}</p>
        </div>
      ))}
    </div>
  )
}

/*
 * The hero shows the product itself: a patient record with the vitals
 * strip and a lab panel — the objects hospital staff look at all day.
 */
function ProductMock() {
  return (
    <div className="w-full max-w-md rounded-xl border border-border bg-card shadow-sm">
      <div className="flex items-center justify-between border-b border-border px-5 py-3.5">
        <div>
          <p className="text-sm font-semibold text-foreground">Chan Dara</p>
          <p className="mt-0.5 text-xs text-muted-foreground">Inpatient · Ward 4, Room 12</p>
        </div>
        <RecordCode value="7EW0L" />
      </div>
      <div className="px-5 py-4">
        <VitalsStrip />
      </div>
      <div className="border-t border-border px-5 py-3.5">
        <p className="text-xs font-medium text-muted-foreground">Latest lab panel</p>
        <div className="mt-2.5 flex items-center justify-between gap-3">
          <div>
            <p className="text-sm font-medium text-foreground">Complete blood count</p>
            <p className="mt-0.5 text-xs text-muted-foreground">Taken today, 09:15</p>
          </div>
          <Badge variant="outline" className="border-success/25 text-success">
            Results in
          </Badge>
        </div>
      </div>
      <div className="border-t border-border px-5 py-3.5">
        <div className="flex items-center justify-between gap-3">
          <div>
            <p className="text-sm font-medium text-foreground">Paracetamol 500 mg</p>
            <p className="mt-0.5 text-xs text-muted-foreground">1 tablet, every 6 hours</p>
          </div>
          <Badge variant="outline" className="border-warning/30 text-warning">
            Pending
          </Badge>
        </div>
      </div>
    </div>
  )
}

export default function Landing() {
  return (
    <div className="min-h-screen bg-background text-foreground">
      {/* Header */}
      <header className="sticky top-0 z-10 border-b border-border bg-background/90 backdrop-blur">
        <div className="mx-auto flex h-14 max-w-6xl items-center justify-between px-4 lg:px-6">
          <div className="flex items-center gap-2.5">
            <span className="flex size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
              <svg viewBox="0 0 100 100" fill="currentColor" className="size-4" aria-hidden="true">
                <path d="M40 18h20v22h22v20H60v22H40V60H18V40h22z" />
              </svg>
            </span>
            <span className="text-sm font-semibold">Hospital System</span>
          </div>
          <Button nativeButton={false} render={<Link to="/login" />}>Sign in</Button>
        </div>
      </header>

      {/* Hero — left-aligned, grounded in the product */}
      <section className="mx-auto max-w-6xl px-4 pb-20 pt-16 lg:px-6 lg:pt-24">
        <div className="grid items-center gap-12 lg:grid-cols-[1.1fr_1fr]">
          <div>
            <h1 className="text-4xl font-semibold leading-[1.1] tracking-tight sm:text-5xl">
              Every patient, prescription and lab result in one system.
            </h1>
            <p className="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground">
              Hospital System keeps the front desk, doctors, laboratory and pharmacy working from the
              same records. Register a patient once — the number, the chart, the vitals and the
              discharge follow from there.
            </p>
            <div className="mt-8 flex flex-wrap items-center gap-3">
              <Button size="lg" nativeButton={false} render={<Link to="/login" />}>
                Sign in
              </Button>
              <Button size="lg" variant="outline" nativeButton={false} render={<a href="#modules" />}>
                See what's inside
              </Button>
            </div>
          </div>
          <div className="flex justify-center lg:justify-end">
            <ProductMock />
          </div>
        </div>
      </section>

      {/* Modules — one bordered record, rows separated by rules */}
      <section id="modules" className="border-t border-border bg-card">
        <div className="mx-auto max-w-6xl px-4 py-16 lg:px-6 lg:py-20">
          <div className="max-w-xl">
            <h2 className="text-2xl font-semibold tracking-tight">What the system covers</h2>
            <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
              Six modules, one set of records. Each role signs in to the part of the hospital it works in.
            </p>
          </div>

          <div className="mt-10 rounded-xl border border-border bg-background">
            {features.map((f, i) => (
              <div key={f.title}>
                {i > 0 && <Separator />}
                <div className="grid gap-3 px-5 py-5 sm:grid-cols-[auto_1fr_auto] sm:items-start sm:gap-6 sm:px-6">
                  <span className="flex size-9 items-center justify-center rounded-md bg-primary/8 text-primary">
                    <f.icon className="size-4.5" strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div className="max-w-xl">
                    <h3 className="text-sm font-semibold">{f.title}</h3>
                    <p className="mt-1 text-sm leading-relaxed text-muted-foreground">{f.description}</p>
                  </div>
                  <p className="text-xs font-medium text-muted-foreground sm:pt-1.5">{f.module}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Roles */}
      <section className="border-t border-border">
        <div className="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-2 lg:px-6">
          <div>
            <h2 className="text-2xl font-semibold tracking-tight">Two sign-ins, one set of records</h2>
            <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
              Administrators sign in with an email address and manage the whole hospital. Doctors sign
              in with their doctor ID and work their own patients, lab tests and prescriptions.
            </p>
          </div>
          <div className="grid gap-4 sm:grid-cols-2">
            <div className="rounded-lg border border-border bg-card p-5">
              <p className="text-sm font-semibold">Administrator</p>
              <p className="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                Full management: patients, doctors, theatre, pharmacy and stock.
              </p>
            </div>
            <div className="rounded-lg border border-border bg-card p-5">
              <p className="text-sm font-semibold">Doctor</p>
              <p className="mt-1.5 text-sm leading-relaxed text-muted-foreground">
                Own workspace: patients, lab tests, vitals and prescriptions.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t border-border">
        <div className="mx-auto flex max-w-6xl flex-col items-start justify-between gap-2 px-4 py-8 text-sm text-muted-foreground sm:flex-row lg:px-6">
          <p>Hospital System</p>
          <p>React and Tailwind CSS on the front, Laravel and MySQL behind it.</p>
        </div>
      </footer>
    </div>
  )
}
