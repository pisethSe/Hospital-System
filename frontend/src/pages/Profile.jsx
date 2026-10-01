import { useState } from 'react'
import { useAuth } from '@/context/AuthContext'
import api from '@/api/client'
import { PageHeader, errorMessage } from '@/components/ui'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import { toast } from 'sonner'

export default function Profile() {
  const { user, isAdmin } = useAuth()

  const [form, setForm] = useState({
    first_name: '',
    last_name: '',
    email: '',
  })
  const [pwd, setPwd] = useState('')
  const [busyProfile, setBusyProfile] = useState(false)
  const [busyPwd, setBusyPwd] = useState(false)

  const current = {
    first_name: user?.name?.split(' ').slice(0, -1).join(' ') || '',
    last_name: user?.name?.split(' ').slice(-1)[0] || '',
    email: user?.email || '',
  }

  const saveProfile = async (e) => {
    e.preventDefault()
    setBusyProfile(true)
    try {
      const payload = {}
      if (form.first_name) payload.first_name = form.first_name
      if (form.last_name) payload.last_name = form.last_name
      if (form.email) payload.email = form.email
      if (Object.keys(payload).length === 0) {
        toast.info('Nothing to change')
        return
      }
      const res = await api.put('/profile', payload)
      toast.success('Account updated')
      // refresh the session user in place
      window.dispatchEvent(new CustomEvent('profile-updated', { detail: res.data.user }))
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusyProfile(false)
    }
  }

  const savePassword = async (e) => {
    e.preventDefault()
    setBusyPwd(true)
    try {
      await api.put('/profile/password', { password: pwd })
      toast.success('Password updated', {
        description: 'Use the new password next time you sign in.',
      })
      setPwd('')
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusyPwd(false)
    }
  }

  return (
    <>
      <PageHeader
        title="My account"
        description="Your details and sign-in password."
      />

      <div className="mt-6 grid max-w-3xl gap-6">
        {/* Details */}
        <Card>
          <CardHeader>
            <CardTitle>Details</CardTitle>
            <CardDescription>
              {isAdmin ? 'Signed in as an administrator.' : 'Signed in as a doctor.'}
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={saveProfile}>
              <FieldGroup className="gap-4">
                <div className="grid gap-4 sm:grid-cols-2">
                  <Field>
                    <FieldLabel htmlFor="first_name">First name</FieldLabel>
                    <Input
                      id="first_name"
                      placeholder={current.first_name || 'First name'}
                      value={form.first_name}
                      onChange={(e) => setForm({ ...form, first_name: e.target.value })}
                    />
                    <FieldDescription>Leave empty to keep the current value.</FieldDescription>
                  </Field>
                  <Field>
                    <FieldLabel htmlFor="last_name">Last name</FieldLabel>
                    <Input
                      id="last_name"
                      placeholder={current.last_name || 'Last name'}
                      value={form.last_name}
                      onChange={(e) => setForm({ ...form, last_name: e.target.value })}
                    />
                  </Field>
                </div>
                <Field>
                  <FieldLabel htmlFor="email">Email</FieldLabel>
                  <Input
                    id="email"
                    type="email"
                    placeholder={current.email || 'Email address'}
                    value={form.email}
                    onChange={(e) => setForm({ ...form, email: e.target.value })}
                  />
                  {isAdmin && <FieldDescription>You sign in with this email address.</FieldDescription>}
                </Field>
              </FieldGroup>
              <div className="mt-6 flex justify-end">
                <Button type="submit" disabled={busyProfile}>
                  {busyProfile ? 'Saving' : 'Save details'}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>

        {/* Password */}
        <Card>
          <CardHeader>
            <CardTitle>Password</CardTitle>
            <CardDescription>Set a new password for your account.</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={savePassword}>
              <FieldGroup className="gap-4">
                <Field>
                  <FieldLabel htmlFor="new_pwd">New password</FieldLabel>
                  <Input
                    id="new_pwd"
                    type="password"
                    required
                    value={pwd}
                    onChange={(e) => setPwd(e.target.value)}
                  />
                  <FieldDescription>
                    {isAdmin
                      ? 'Applies to your administrator account.'
                      : 'Applies to your doctor account — you still sign in with your doctor ID.'}
                  </FieldDescription>
                </Field>
              </FieldGroup>
              <div className="mt-6 flex justify-end">
                <Button type="submit" disabled={busyPwd || !pwd}>
                  {busyPwd ? 'Saving' : 'Update password'}
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>

        <p className="text-xs text-muted-foreground">
          Sign-in rules from the original system apply: administrators sign in with email, doctors with their doctor ID.
        </p>
      </div>
    </>
  )
}
