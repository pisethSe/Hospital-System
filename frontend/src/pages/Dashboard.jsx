import { Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { Badge, EmptyState, PageHeader, Spinner, StatCard, useApi } from '../components/ui'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  })
}

export default function Dashboard() {
  const { user, isAdmin } = useAuth()
  const { data, loading, error } = useApi('/dashboard')

  if (loading) {
    return (
      <div className="flex justify-center py-20">
        <Spinner className="h-8 w-8" />
      </div>
    )
  }

  if (error) return <EmptyState message={error} />

  const stats = data?.stats || {}

  return (
    <>
      <PageHeader
        title={`Welcome back, ${user?.name?.split(' ')[0] || 'there'}`}
        subtitle={isAdmin ? "Here's what's happening in your hospital today." : "Here's your workspace overview."}
      />

      <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard label="Total patients" value={stats.patients} />
        <StatCard label="Active patients" value={stats.active_patients} accent="sky" />
        {isAdmin && <StatCard label="Doctors" value={stats.doctors} accent="violet" />}
        <StatCard label="Lab tests" value={stats.lab_tests} accent="amber" />
        <StatCard label="Pending lab results" value={stats.pending_lab_tests} accent="rose" />
        {isAdmin && <StatCard label="Surgeries" value={stats.surgeries} />}
        <StatCard label="Prescriptions" value={stats.prescriptions} accent="violet" />
        {isAdmin && <StatCard label="Medicines in stock" value={stats.medicines} accent="sky" />}
      </div>

      <div className={`mt-8 grid gap-6 ${isAdmin ? 'xl:grid-cols-3' : 'xl:grid-cols-2'}`}>
        {/* Recent patients */}
        <section className="rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
          <div className="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 className="text-sm font-semibold text-slate-900">Recent patients</h2>
            <Link to="./patients" className="text-xs font-semibold text-brand-600 hover:text-brand-700">
              View all →
            </Link>
          </div>
          {data?.recent_patients?.length ? (
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead>
                  <tr className="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                    <th className="px-5 py-3 font-semibold">Patient</th>
                    <th className="px-5 py-3 font-semibold">Number</th>
                    <th className="px-5 py-3 font-semibold">Ailment</th>
                    <th className="px-5 py-3 font-semibold">Joined</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {data.recent_patients.map((p) => (
                    <tr key={p.pat_id} className="hover:bg-slate-50">
                      <td className="px-5 py-3 font-medium text-slate-800">
                        {`${p.pat_fname} ${p.pat_lname}`.trim()}
                      </td>
                      <td className="px-5 py-3 font-mono text-xs text-slate-500">{p.pat_number}</td>
                      <td className="px-5 py-3 text-slate-600">{p.pat_ailment || '–'}</td>
                      <td className="px-5 py-3 text-slate-500">{fmtDate(p.pat_date_joined)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ) : (
            <div className="p-5">
              <EmptyState message="No patients registered yet." />
            </div>
          )}
        </section>

        {/* Recent lab tests */}
        <section className="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div className="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 className="text-sm font-semibold text-slate-900">Recent lab tests</h2>
            <Link to="./lab-tests" className="text-xs font-semibold text-brand-600 hover:text-brand-700">
              View all →
            </Link>
          </div>
          <div className="divide-y divide-slate-100">
            {data?.recent_lab_tests?.length ? (
              data.recent_lab_tests.map((l) => (
                <div key={l.lab_id} className="flex items-start justify-between gap-3 px-5 py-3.5">
                  <div className="min-w-0">
                    <p className="truncate text-sm font-medium text-slate-800">{l.lab_pat_name}</p>
                    <p className="mt-0.5 text-xs text-slate-500">
                      #{l.lab_number} · {fmtDate(l.lab_date_rec)}
                    </p>
                  </div>
                  <Badge text={l.lab_pat_results ? 'Completed' : 'Pending'} />
                </div>
              ))
            ) : (
              <div className="p-5">
                <EmptyState message="No lab tests yet." />
              </div>
            )}
          </div>
        </section>
      </div>

      {/* Pending surgeries (admin only) */}
      {isAdmin && data?.pending_surgeries?.length > 0 && (
        <section className="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
          <div className="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 className="text-sm font-semibold text-slate-900">Latest theatre patients</h2>
            <Link to="./surgeries" className="text-xs font-semibold text-brand-600 hover:text-brand-700">
              View all →
            </Link>
          </div>
          <div className="divide-y divide-slate-100">
            {data.pending_surgeries.map((s) => (
              <div key={s.s_id} className="flex items-center justify-between gap-3 px-5 py-3.5">
                <div>
                  <p className="text-sm font-medium text-slate-800">
                    {s.s_pat_name} <span className="text-slate-400">· surgeon {s.s_doc || '–'}</span>
                  </p>
                  <p className="mt-0.5 text-xs text-slate-500">
                    #{s.s_number} · {fmtDate(s.s_pat_date)}
                  </p>
                </div>
                <Badge text={s.s_pat_status} />
              </div>
            ))}
          </div>
        </section>
      )}
    </>
  )
}
