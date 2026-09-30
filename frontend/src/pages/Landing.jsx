import { Link } from 'react-router-dom'

const features = [
  {
    title: 'Patient Management',
    desc: 'Register patients, track rooms, ailments and discharge records — with auto-generated patient numbers.',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  },
  {
    title: 'Doctor Panel',
    desc: 'Doctors get their own workspace for lab tests, prescriptions and patient vitals.',
    icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
  },
  {
    title: 'Laboratory',
    desc: 'Request lab tests, record results and track pending work in one place.',
    icon: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
  },
  {
    title: 'Pharmacy',
    desc: 'Manage pharmaceuticals, categories, vendors and stock quantities.',
    icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
  },
  {
    title: 'Surgery / Theatre',
    desc: 'Schedule theatre patients, assign surgeons and track surgery status.',
    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
  },
  {
    title: 'Patient Vitals',
    desc: 'Record body temperature, pulse, respiration rate and blood pressure over time.',
    icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
  },
]

const departments = [
  'General Medicine',
  'Laboratory',
  'Surgery',
  'Pharmacy',
  'Radiology',
  'Emergency',
  'Pediatrics',
  'Cardiology',
]

export default function Landing() {
  return (
    <div className="min-h-screen bg-slate-950 text-white">
      {/* Navbar */}
      <header className="sticky top-0 z-10 border-b border-white/10 bg-slate-950/80 backdrop-blur">
        <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-4">
          <div className="flex items-center gap-2.5">
            <span className="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-white">
              <svg className="h-5 w-5" viewBox="0 0 100 100" fill="currentColor">
                <path d="M40 18h20v22h22v20H60v22H40V60H18V40h22z" />
              </svg>
            </span>
            <span className="text-base font-semibold">Hospital System</span>
          </div>
          <Link
            to="/login"
            className="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-400"
          >
            Sign in
          </Link>
        </div>
      </header>

      {/* Hero */}
      <section className="relative overflow-hidden">
        <div className="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[900px] -translate-x-1/2 rounded-full bg-brand-500/20 blur-3xl" />
        <div className="relative mx-auto max-w-6xl px-4 py-24 text-center lg:py-32">
          <p className="mx-auto mb-5 inline-flex rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-300">
            Hospital Management System
          </p>
          <h1 className="mx-auto max-w-3xl text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
            Run your hospital <span className="text-brand-400">smarter</span>, from front desk to pharmacy.
          </h1>
          <p className="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-slate-400 sm:text-lg">
            One system for patients, doctors, laboratory, surgery, prescriptions and pharmacy — with a clean
            React interface and a secure Laravel API.
          </p>
          <div className="mt-10 flex flex-wrap items-center justify-center gap-4">
            <Link
              to="/login"
              className="rounded-lg bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-400"
            >
              Open the dashboard
            </Link>
            <a
              href="#features"
              className="rounded-lg border border-white/15 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:bg-white/5"
            >
              Explore features
            </a>
          </div>

          <dl className="mx-auto mt-16 grid max-w-3xl grid-cols-3 gap-4 text-center">
            {[
              ['Admin', 'Full management'],
              ['Doctors', 'Own workspace'],
              ['Roles', 'Secure access'],
            ].map(([value, label]) => (
              <div key={label} className="rounded-xl border border-white/10 bg-white/5 px-4 py-5">
                <dt className="text-xl font-bold text-brand-300 sm:text-2xl">{value}</dt>
                <dd className="mt-1 text-xs text-slate-400 sm:text-sm">{label}</dd>
              </div>
            ))}
          </dl>
        </div>
      </section>

      {/* Features */}
      <section id="features" className="mx-auto max-w-6xl px-4 py-20">
        <h2 className="text-center text-3xl font-bold">Everything your hospital needs</h2>
        <p className="mx-auto mt-3 max-w-xl text-center text-slate-400">
          Modules carried over from the original system, rebuilt on a modern stack.
        </p>
        <div className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((f) => (
            <div
              key={f.title}
              className="rounded-2xl border border-white/10 bg-white/5 p-6 transition hover:border-brand-400/40 hover:bg-white/[0.07]"
            >
              <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-500/15 text-brand-300">
                <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.8}>
                  <path strokeLinecap="round" strokeLinejoin="round" d={f.icon} />
                </svg>
              </span>
              <h3 className="mt-4 text-lg font-semibold">{f.title}</h3>
              <p className="mt-2 text-sm leading-relaxed text-slate-400">{f.desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Departments */}
      <section className="border-t border-white/10 bg-white/[0.02]">
        <div className="mx-auto max-w-6xl px-4 py-16 text-center">
          <h2 className="text-2xl font-bold">Departments</h2>
          <div className="mt-8 flex flex-wrap justify-center gap-3">
            {departments.map((d) => (
              <span
                key={d}
                className="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300"
              >
                {d}
              </span>
            ))}
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t border-white/10">
        <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-4 py-8 text-sm text-slate-500 sm:flex-row">
          <p>&copy; {new Date().getFullYear()} Hospital Management System</p>
          <p>
            React + Tailwind CSS frontend · Laravel API · MySQL database
          </p>
        </div>
      </footer>
    </div>
  )
}
