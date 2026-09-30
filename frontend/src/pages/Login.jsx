import { useState } from 'react'
import { Link, Navigate, useNavigate } from 'react-router-dom'
import { useAuth } from '@/context/AuthContext'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { Spinner } from '@/components/ui/spinner'
import { CircleAlert } from 'lucide-react'

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
      // Sign-in logic preserved from the original system: administrators
      // use email + password, doctors use their doctor ID + password.
      const credentials = isDoctor
        ? { doc_number: form.identifier, password: form.password }
        : { email: form.identifier, password: form.password }

      await login(role, credentials)
      navigate(isDoctor ? '/doctor' : '/admin', { replace: true })
    } catch (err) {
      setError(err.response?.data?.message || 'Sign-in failed. Check your details and try again.')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="flex min-h-screen flex-col bg-background text-foreground">
      <header className="border-b border-border">
        <div className="mx-auto flex h-14 max-w-6xl items-center px-4 lg:px-6">
          <Link to="/" className="flex items-center gap-2.5">
            <span className="flex size-8 items-center justify-center rounded-md bg-primary text-primary-foreground">
              <svg viewBox="0 0 100 100" fill="currentColor" className="size-4" aria-hidden="true">
                <path d="M40 18h20v22h22v20H60v22H40V60H18V40h22z" />
              </svg>
            </span>
            <span className="text-sm font-semibold">Hospital System</span>
          </Link>
        </div>
      </header>

      <main className="flex flex-1 items-start justify-center px-4 pb-16 pt-14">
        <div className="w-full max-w-sm">
          <h1 className="text-xl font-semibold tracking-tight">Sign in</h1>
          <p className="mt-1.5 text-sm text-muted-foreground">
            Choose your role, then enter your details.
          </p>

          <Tabs
            value={role}
            onValueChange={(value) => {
              setRole(value)
              setError(null)
            }}
            className="mt-6"
          >
            <TabsList className="grid w-full grid-cols-2">
              <TabsTrigger value="admin">Administrator</TabsTrigger>
              <TabsTrigger value="doctor">Doctor</TabsTrigger>
            </TabsList>
          </Tabs>

          <form onSubmit={handleSubmit} className="mt-5">
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="identifier">{isDoctor ? 'Doctor ID' : 'Email address'}</FieldLabel>
                <Input
                  id="identifier"
                  type={isDoctor ? 'text' : 'email'}
                  required
                  autoComplete="username"
                  placeholder={isDoctor ? 'pkd' : 'admin@hospital.com'}
                  value={form.identifier}
                  onChange={(e) => setForm({ ...form, identifier: e.target.value })}
                />
                {isDoctor && <FieldDescription>The ID printed on your account, e.g. pkd or visal.</FieldDescription>}
              </Field>

              <Field>
                <FieldLabel htmlFor="password">Password</FieldLabel>
                <Input
                  id="password"
                  type="password"
                  required
                  autoComplete="current-password"
                  placeholder="Your password"
                  value={form.password}
                  onChange={(e) => setForm({ ...form, password: e.target.value })}
                />
              </Field>

              {error && (
                <Alert variant="destructive">
                  <CircleAlert />
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}

              <Button type="submit" disabled={submitting} className="w-full">
                {submitting && <Spinner data-icon="inline-start" />}
                {submitting ? 'Signing in' : 'Sign in'}
              </Button>
            </FieldGroup>
          </form>

          <p className="mt-6 text-xs leading-relaxed text-muted-foreground">
            Demo administrator: admin@hospital.com, password admin123.
            Demo doctor ID: pkd, password pkd123.
          </p>
        </div>
      </main>
    </div>
  )
}
