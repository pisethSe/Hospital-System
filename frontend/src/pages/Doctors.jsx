import { useState } from 'react'
import api from '@/api/client'
import {
  ConfirmDialog,
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
  PageHeader,
  RecordCode,
  errorMessage,
  useApi,
} from '@/components/ui'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { Search, Stethoscope } from 'lucide-react'

const emptyForm = {
  doc_fname: '',
  doc_lname: '',
  doc_email: '',
  doc_dept: '',
  doc_number: '',
  password: '',
}

export default function Doctors() {
  const { data, loading, error, reload } = useApi('/doctors')
  const [search, setSearch] = useState('')

  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [busy, setBusy] = useState(false)

  const [confirm, setConfirm] = useState(null)
  const [confirmBusy, setConfirmBusy] = useState(false)

  const doctors = (data?.data || []).filter((d) =>
    `${d.doc_fname} ${d.doc_lname} ${d.doc_number} ${d.doc_dept}`.toLowerCase().includes(search.toLowerCase()),
  )

  const openAdd = () => {
    setForm(emptyForm)
    setEditing(null)
    setDialogOpen(true)
  }

  const openEdit = (d) => {
    setEditing(d)
    setForm({
      doc_fname: d.doc_fname || '',
      doc_lname: d.doc_lname || '',
      doc_email: d.doc_email || '',
      doc_dept: d.doc_dept || '',
      doc_number: d.doc_number || '',
      password: '',
    })
    setDialogOpen(true)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (editing) {
        const payload = { ...form }
        if (!payload.password) delete payload.password
        await api.put(`/doctors/${editing.doc_id}`, payload)
        toast.success('Doctor details updated')
      } else {
        const res = await api.post('/doctors', form)
        toast.success('Doctor added', {
          description: `${res.data?.data?.doc_fname || form.doc_fname} signs in with ID ${res.data?.data?.doc_number}.`,
        })
      }
      setDialogOpen(false)
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const runRemove = async () => {
    if (!confirm) return
    setConfirmBusy(true)
    try {
      await api.delete(`/doctors/${confirm.doc_id}`)
      toast.success('Doctor removed')
      setConfirm(null)
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setConfirmBusy(false)
    }
  }

  return (
    <>
      <PageHeader
        title="Doctors"
        description="Doctor accounts, departments and sign-in IDs."
        action={<Button onClick={openAdd}>Add doctor</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search doctors"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-8"
        />
      </div>

      {loading ? (
        <div className="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {Array.from({ length: 6 }).map((_, i) => (
            <Skeleton key={i} className="h-40 w-full rounded-xl" />
          ))}
        </div>
      ) : error ? (
        <Empty className="mt-6">
          <EmptyHeader>
            <EmptyTitle>Could not load doctors</EmptyTitle>
            <EmptyDescription>{error}</EmptyDescription>
          </EmptyHeader>
        </Empty>
      ) : doctors.length === 0 ? (
        <Empty className="mt-6 border-0">
          <EmptyHeader>
            <EmptyMedia variant="icon">
              <Stethoscope />
            </EmptyMedia>
            <EmptyTitle>No doctors found</EmptyTitle>
            <EmptyDescription>
              {search ? 'No accounts match the search.' : 'Add the first doctor to get started.'}
            </EmptyDescription>
          </EmptyHeader>
          {!search && <Button onClick={openAdd}>Add doctor</Button>}
        </Empty>
      ) : (
        <div className="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {doctors.map((d) => (
            <div key={d.doc_id} className="flex flex-col rounded-xl border border-border bg-card p-5">
              <div className="flex items-center gap-3">
                <span className="flex size-10 items-center justify-center rounded-md bg-primary/8 text-sm font-semibold text-primary">
                  {`${d.doc_fname?.[0] || ''}${d.doc_lname?.[0] || ''}`.toUpperCase() || 'D'}
                </span>
                <div className="min-w-0">
                  <p className="truncate text-sm font-semibold">{`${d.doc_fname} ${d.doc_lname}`.trim()}</p>
                  <p className="truncate text-xs text-muted-foreground">{d.doc_dept || 'No department'}</p>
                </div>
              </div>

              <dl className="mt-4 flex flex-col gap-1.5 text-sm">
                <div className="flex items-center justify-between gap-3">
                  <dt className="text-muted-foreground">Signs in with</dt>
                  <dd><RecordCode value={d.doc_number} /></dd>
                </div>
                <div className="flex items-center justify-between gap-3">
                  <dt className="text-muted-foreground">Email</dt>
                  <dd className="truncate text-foreground">{d.doc_email || '–'}</dd>
                </div>
              </dl>

              <div className="mt-4 flex justify-end gap-1.5 pt-1">
                <Button variant="outline" size="sm" onClick={() => openEdit(d)}>
                  Edit
                </Button>
                <Button variant="destructive" size="sm" onClick={() => setConfirm(d)}>
                  Remove
                </Button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Add / edit dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{editing ? 'Edit doctor' : 'Add doctor'}</DialogTitle>
            <DialogDescription>
              {editing
                ? 'Leave the password field empty to keep the current password.'
                : 'The doctor signs in with this ID and the password you set here.'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="doc_fname">First name</FieldLabel>
                  <Input
                    id="doc_fname"
                    required
                    value={form.doc_fname}
                    onChange={(e) => setForm({ ...form, doc_fname: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="doc_lname">Last name</FieldLabel>
                  <Input
                    id="doc_lname"
                    value={form.doc_lname}
                    onChange={(e) => setForm({ ...form, doc_lname: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="doc_email">Email</FieldLabel>
                  <Input
                    id="doc_email"
                    type="email"
                    value={form.doc_email}
                    onChange={(e) => setForm({ ...form, doc_email: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="doc_dept">Department</FieldLabel>
                  <Input
                    id="doc_dept"
                    placeholder="e.g. General Medicine"
                    value={form.doc_dept}
                    onChange={(e) => setForm({ ...form, doc_dept: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="doc_number">Sign-in ID</FieldLabel>
                  <Input
                    id="doc_number"
                    placeholder="Generated if left empty"
                    value={form.doc_number}
                    onChange={(e) => setForm({ ...form, doc_number: e.target.value })}
                  />
                  <FieldDescription>Doctors type this ID to sign in.</FieldDescription>
                </Field>
                <Field>
                  <FieldLabel htmlFor="doc_password">{editing ? 'New password' : 'Password'}</FieldLabel>
                  <Input
                    id="doc_password"
                    type="password"
                    required={!editing}
                    placeholder={editing ? 'Leave empty to keep current' : 'Set a password'}
                    value={form.password}
                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                  />
                </Field>
              </div>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : editing ? 'Save changes' : 'Add doctor'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      <ConfirmDialog
        open={Boolean(confirm)}
        onOpenChange={(open) => !open && setConfirm(null)}
        destructive
        busy={confirmBusy}
        title="Remove this doctor?"
        description={`${confirm?.doc_fname || ''} ${confirm?.doc_lname || ''} will lose access to the system. This cannot be undone.`}
        confirmLabel="Remove doctor"
        onConfirm={runRemove}
      />
    </>
  )
}
