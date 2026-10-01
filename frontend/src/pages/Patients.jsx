import { useEffect, useState } from 'react'
import { useAuth } from '@/context/AuthContext'
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
  StatCard,
  errorMessage,
  useApi,
} from '@/components/ui'
import { Spinner } from '@/components/ui/spinner'
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
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { CircleUserRound, Search } from 'lucide-react'

const emptyForm = {
  pat_fname: '',
  pat_lname: '',
  pat_dob: '',
  pat_age: '',
  pat_phone: '',
  pat_type: 'Outpatient',
  pat_addr: '',
  pat_ailment: '',
  pat_room_number: '',
}

export default function Patients() {
  const { isAdmin } = useAuth()
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('')
  const [page, setPage] = useState(1)

  const { data, loading, error, reload } = useApi(
    `/patients?search=${encodeURIComponent(search)}&status=${status}&page=${page}&per_page=10`,
  )

  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [busy, setBusy] = useState(false)

  const [confirm, setConfirm] = useState(null) // { kind: 'discharge' | 'delete', patient }
  const [confirmBusy, setConfirmBusy] = useState(false)

  const patients = data?.data?.data || []
  const meta = data?.data

  // After removing the last record on a page, snap back to the last
  // valid page instead of showing an empty one.
  useEffect(() => {
    if (!loading && meta && meta.total > 0 && meta.current_page > meta.last_page) {
      // Deliberate one-time correction after fresh data has loaded,
      // not a cascading render.
      // eslint-disable-next-line react-hooks/set-state-in-effect
      setPage(meta.last_page)
    }
  }, [loading, meta])

  const openAdd = () => {
    setForm(emptyForm)
    setEditing(null)
    setDialogOpen(true)
  }

  const openEdit = (p) => {
    setEditing(p)
    setForm({
      pat_fname: p.pat_fname || '',
      pat_lname: p.pat_lname || '',
      pat_dob: p.pat_dob || '',
      pat_age: p.pat_age || '',
      pat_phone: p.pat_phone || '',
      pat_type: p.pat_type || '',
      pat_addr: p.pat_addr || '',
      pat_ailment: p.pat_ailment || '',
      pat_room_number: p.pat_room_number || '',
    })
    setDialogOpen(true)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (editing) {
        await api.put(`/patients/${editing.pat_id}`, form)
        toast.success('Patient details updated')
      } else {
        await api.post('/patients', form)
        toast.success('Patient registered', {
          description: `${form.pat_fname} ${form.pat_lname} was added to the books.`,
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

  const runConfirm = async () => {
    if (!confirm) return
    setConfirmBusy(true)
    try {
      if (confirm.kind === 'discharge') {
        await api.post(`/patients/${confirm.patient.pat_id}/discharge`)
        toast.success('Patient discharged')
      } else {
        await api.delete(`/patients/${confirm.patient.pat_id}`)
        toast.success('Patient removed')
      }
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
        title="Patients"
        description="Everyone on the books — registered, in a ward, or discharged."
        action={
          isAdmin && (
            <Button onClick={openAdd}>Register patient</Button>
          )
        }
      />

      <div className="mt-6 grid gap-4 sm:grid-cols-3">
        <StatCard label="Showing" value={meta?.total ?? '–'} hint="records match the current filter" />
        <StatCard label="Page" value={`${meta?.current_page ?? '–'} of ${meta?.last_page ?? '–'}`} />
        <StatCard label="View" value={status === 'active' ? 'Active' : status === 'discharged' ? 'Discharged' : 'All'} />
      </div>

      {/* Filters */}
      <div className="mt-6 flex flex-wrap items-center gap-3">
        <div className="relative w-full max-w-xs">
          <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input
            type="search"
            placeholder="Search name, record number or ailment"
            value={search}
            onChange={(e) => {
              setSearch(e.target.value)
              setPage(1)
            }}
            className="pl-8"
          />
        </div>
        <Select
          value={status}
          onValueChange={(value) => {
            setStatus(value === 'all' ? '' : value)
            setPage(1)
          }}
        >
          <SelectTrigger className="w-44" aria-label="Filter by status">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">All patients</SelectItem>
            <SelectItem value="active">Active only</SelectItem>
            <SelectItem value="discharged">Discharged only</SelectItem>
          </SelectContent>
        </Select>
      </div>

      {/* Table */}
      <div className="mt-4 rounded-xl border border-border bg-card">
        {loading ? (
          <div className="flex flex-col gap-2 p-4">
            {Array.from({ length: 6 }).map((_, i) => (
              <Skeleton key={i} className="h-11 w-full" />
            ))}
          </div>
        ) : error ? (
          <Empty>
            <EmptyHeader>
              <EmptyTitle>Could not load patients</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Patient</TableHead>
                <TableHead>Record no.</TableHead>
                <TableHead>Type</TableHead>
                <TableHead>Ailment</TableHead>
                <TableHead>Room</TableHead>
                <TableHead>Status</TableHead>
                {isAdmin && <TableHead className="text-right">Actions</TableHead>}
              </TableRow>
            </TableHeader>
            <TableBody>
              {patients.map((p) => {
                const discharged = Boolean(p.pat_walk_out_date)
                return (
                  <TableRow key={p.pat_id}>
                    <TableCell>
                      <p className="font-medium">{`${p.pat_fname} ${p.pat_lname}`.trim()}</p>
                      <p className="mt-0.5 text-xs text-muted-foreground">{p.pat_phone || 'No phone'}</p>
                    </TableCell>
                    <TableCell><RecordCode value={p.pat_number} /></TableCell>
                    <TableCell className="text-muted-foreground">{p.pat_type || '–'}</TableCell>
                    <TableCell className="text-muted-foreground">{p.pat_ailment || '–'}</TableCell>
                    <TableCell className="tabular-nums text-muted-foreground">{p.pat_room_number || '–'}</TableCell>
                    <TableCell>
                      <span className={discharged ? 'text-sm text-success' : 'text-sm font-medium text-foreground'}>
                        {discharged ? 'Discharged' : p.pat_discharge_status || 'Active'}
                      </span>
                    </TableCell>
                    {isAdmin && (
                      <TableCell>
                        <div className="flex justify-end gap-1.5">
                          {!discharged && (
                            <Button
                              variant="outline"
                              size="sm"
                              onClick={() => setConfirm({ kind: 'discharge', patient: p })}
                            >
                              Discharge
                            </Button>
                          )}
                          <Button variant="outline" size="sm" onClick={() => openEdit(p)}>
                            Edit
                          </Button>
                          <Button
                            variant="destructive"
                            size="sm"
                            onClick={() => setConfirm({ kind: 'delete', patient: p })}
                          >
                            Remove
                          </Button>
                        </div>
                      </TableCell>
                    )}
                  </TableRow>
                )
              })}
              {patients.length === 0 && (
                <TableRow>
                  <TableCell colSpan={isAdmin ? 7 : 6}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <CircleUserRound />
                        </EmptyMedia>
                        <EmptyTitle>No patients found</EmptyTitle>
                        <EmptyDescription>
                          {search || status
                            ? 'No records match the current search or filter.'
                            : 'Register the first patient to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {isAdmin && !search && !status && (
                        <Button onClick={openAdd}>Register patient</Button>
                      )}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}

        {/* Pagination */}
        {meta && meta.last_page > 1 && !loading && (
          <div className="flex items-center justify-between border-t border-border px-4 py-3">
            <p className="text-sm text-muted-foreground">
              Page {meta.current_page} of {meta.last_page}
            </p>
            <div className="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                disabled={meta.current_page <= 1}
                onClick={() => setPage((n) => n - 1)}
              >
                Previous
              </Button>
              <Button
                variant="outline"
                size="sm"
                disabled={meta.current_page >= meta.last_page}
                onClick={() => setPage((n) => n + 1)}
              >
                Next
              </Button>
            </div>
          </div>
        )}
      </div>

      {/* Register / edit dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="sm:max-w-2xl">
          <DialogHeader>
            <DialogTitle>{editing ? 'Edit patient' : 'Register patient'}</DialogTitle>
            <DialogDescription>
              {editing
                ? 'Update the patient details. Changes save to the same record.'
                : 'The record number is generated for you when the patient is saved.'}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="pat_fname">First name</FieldLabel>
                  <Input
                    id="pat_fname"
                    required
                    value={form.pat_fname}
                    onChange={(e) => setForm({ ...form, pat_fname: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_lname">Last name</FieldLabel>
                  <Input
                    id="pat_lname"
                    required
                    value={form.pat_lname}
                    onChange={(e) => setForm({ ...form, pat_lname: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_phone">Phone</FieldLabel>
                  <Input
                    id="pat_phone"
                    value={form.pat_phone}
                    onChange={(e) => setForm({ ...form, pat_phone: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_type">Patient type</FieldLabel>
                  <Select value={form.pat_type} onValueChange={(value) => setForm({ ...form, pat_type: value })}>
                    <SelectTrigger id="pat_type" className="w-full">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Outpatient">Outpatient</SelectItem>
                      <SelectItem value="Inpatient">Inpatient</SelectItem>
                    </SelectContent>
                  </Select>
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_dob">Date of birth</FieldLabel>
                  <Input
                    id="pat_dob"
                    type="date"
                    value={form.pat_dob}
                    onChange={(e) => setForm({ ...form, pat_dob: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_age">Age</FieldLabel>
                  <Input
                    id="pat_age"
                    value={form.pat_age}
                    onChange={(e) => setForm({ ...form, pat_age: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_ailment">Ailment</FieldLabel>
                  <Input
                    id="pat_ailment"
                    value={form.pat_ailment}
                    onChange={(e) => setForm({ ...form, pat_ailment: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pat_room_number">Room number</FieldLabel>
                  <Input
                    id="pat_room_number"
                    value={form.pat_room_number}
                    onChange={(e) => setForm({ ...form, pat_room_number: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="pat_addr">Address</FieldLabel>
                <Input
                  id="pat_addr"
                  value={form.pat_addr}
                  onChange={(e) => setForm({ ...form, pat_addr: e.target.value })}
                />
              </Field>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy && <Spinner data-icon="inline-start" />}
                {editing ? 'Save changes' : 'Register patient'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Discharge / remove confirmation */}
      <ConfirmDialog
        open={Boolean(confirm)}
        onOpenChange={(open) => !open && setConfirm(null)}
        destructive={confirm?.kind === 'delete'}
        busy={confirmBusy}
        title={confirm?.kind === 'delete' ? 'Remove this patient?' : 'Discharge this patient?'}
        description={
          confirm?.kind === 'delete'
            ? `${confirm?.patient?.pat_fname} ${confirm?.patient?.pat_lname} will be removed from the books. This cannot be undone.`
            : 'The discharge date is recorded and the patient leaves the active list.'
        }
        confirmLabel={confirm?.kind === 'delete' ? 'Remove patient' : 'Discharge patient'}
        onConfirm={runConfirm}
      />
    </>
  )
}
