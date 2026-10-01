import { useRef, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '@/context/AuthContext'
import api from '@/api/client'
import { PageHeader, errorMessage } from '@/components/ui'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { Separator } from '@/components/ui/separator'
import { toast } from 'sonner'

export default function Profile() {
  const { user, isAdmin, logout } = useAuth()
  const navigate = useNavigate()
  const fileInput = useRef(null)

  const [form, setForm] = useState({
    first_name: '',
    last_name: '',
    email: '',
  })
  const [pwd, setPwd] = useState('')
  const [busyProfile, setBusyProfile] = useState(false)
  const [busyPwd, setBusyPwd] = useState(false)
  const [busyPhoto, setBusyPhoto] = useState(false)
  const [avatar, setAvatar] = useState(user?.avatar || null)

  const current = {
    first_name: user?.name?.split(' ').slice(0, -1).join(' ') || '',
    last_name: user?.name?.split(' ').slice(-1)[0] || '',
    email: user?.email || '',
  }

  const initials = (user?.name || '?')
    .split(' ')
    .map((part) => part[0])
    .filter(Boolean)
    .slice(0, 2)
    .join('')
    .toUpperCase()

  const uploadPhoto = async (e) => {
    const file = e.target.files?.[0]
    if (!file) return
    setBusyPhoto(true)
    try {
      const data = new FormData()
      data.append('photo', file)
      const res = await api.post('/profile/avatar', data, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      setAvatar(res.data.avatar)
      toast.success('Photo uploaded')
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusyPhoto(false)
      if (fileInput.current) fileInput.current.value = ''
    }
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
      toast.success('Password updated')
      // All tokens were revoked with the password change — sign back in.
      await logout()
      navigate('/login')
    } catch (err) {
      toast.error(errorMessage(err))
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
            <div className="flex items-center gap-4 pb-5">
              <Avatar className="size-14">
                {avatar && <img src={`/storage/${avatar}`} alt="Profile photo" className="size-full object-cover" />}
                {!avatar && <AvatarFallback className="bg-primary/10 text-sm font-semibold text-primary">{initials}</AvatarFallback>}
              </Avatar>
              <div>
                <p className="text-sm font-medium">
                  {avatar ? 'Photo on file' : 'No photo yet'}
                </p>
                <div className="mt-1.5">
                  <input
                    ref={fileInput}
                    type="file"
                    accept="image/png,image/jpeg,image/gif,image/webp"
                    className="hidden"
                    onChange={uploadPhoto}
                  />
                  <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    disabled={busyPhoto}
                    onClick={() => fileInput.current?.click()}
                  >
                    {busyPhoto ? 'Uploading' : 'Upload photo'}
                  </Button>
                </div>
              </div>
            </div>
            <Separator />
            <form onSubmit={saveProfile} className="pt-5">
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
