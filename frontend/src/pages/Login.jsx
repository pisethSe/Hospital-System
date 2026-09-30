import { useState } from 'react'
import { Link, Navigate, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { ErrorAlert, Field, inputClass, Spinner } from '../components/ui'

export default function Login() {
  const { user, login, loading } = useAuth()
  const navigate = useNavigate()

  const [role, setRole] = useState('admin')
  const [form, setForm] = useState({ identifier: '', password: '' })
  const [error, setError] = useState(null)
  const [submitting, setSubmitting] = useState(false)

  if (!loading && user) {
    return <Navigate to={user.role === 'admin' ? '/admin' : '/doctor'} replace />
  }

  const isDoctor = role === 'doctor'

  const handleSubmit = async (e) => {
    e.preventDefault()
    setError(null)
    setSubmitting(true)
    try {
      // Legacy logic preserved: admins log in with email + password,
      // doctors log in with their doctor ID (doc_number) + password.
      const credentials = isDoctor
        ? { doc_number: form.identifier, password: form.password }
        : { email: form.identifier, password: form.password }

      await login(role, credentials)
      navigate(isDoctor ? '/doctor' : '/admin', { replace: true })
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed. Please try again.')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-950 px-4 py-10">
      <div className="w-full max-w-md">
        <div className="mb-8 text-center">
          <Link to="/" className="inline-flex items-center gap-2.5">
            <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-500 text-white">
              <svg className="h-6 w-6" viewBox="0 0 100 100" fill="currentColor">
                <path d="M40 18h20v22h22v20H60v22H40V60H18V40h22z" />
              </svg>
            </span>
          </Link>
          <h1 className="mt-4 text-2xl font-bold text-white">Hospital Management System</h1>
          <p className="mt-1 text-sm text-slate-400">Sign in to your workspace</p>
        </div>

        <div className="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur">
          {/* Role tabs */}
          <div className="mb-6 grid grid-cols-2 gap-1 rounded-lg bg-slate-900/60 p-1">
            {['admin', 'doctor'].map((r) => (
              <button
                key={r}
                type="button"
                onClick={() => {
                  setRole(r)
                  setError(null)
                }}
                className={`rounded-md px-3 py-2 text-sm font-semibold capitalize transition ${
                  role === r ? 'bg-brand-500 text-white shadow' : 'text-slate-400 hover:text-white'
                }`}
              >
                {r}
              </button>
            ))}
          </div>

          <ErrorAlert message={error} />

          <form onSubmit={handleSubmit} className="space-y-4">
            <Field label={isDoctor ? 'Doctor ID' : 'Email address'} required>
              <input
                type={isDoctor ? 'text' : 'email'}
                required
                value={form.identifier}
                onChange={(e) => setForm({ ...form, identifier: e.target.value })}
                placeholder={isDoctor ? 'e.g. pkd' : 'admin@hospital.com'}
                className={`${inputClass} border-white/10 bg-slate-900/60 text-white placeholder-slate-500 focus:border-brand-400`}
                autoComplete="username"
              />
            </Field>

            <Field label="Password" required>
              <input
                type="password"
                required
                value={form.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
                placeholder="••••••••"
                className={`${inputClass} border-white/10 bg-slate-900/60 text-white placeholder-slate-500 focus:border-brand-400`}
                autoComplete="current-password"
              />
            </Field>

            <button
              type="submit"
              disabled={submitting}
              className="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-400 disabled:opacity-60"
            >
              {submitting && <Spinner className="h-4 w-4 text-white" />}
              {submitting ? 'Signing in…' : `Sign in as ${role}`}
            </button>
          </form>
        </div>

        <p className="mt-6 text-center text-xs text-slate-500">
          Demo admin: admin@hospital.com / admin123 · Demo doctor ID: pkd / pkd123
        </p>
      </div>
    </div>
  )
}
