import { useState } from 'react'
import api from '@/api/client'
import {
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
import { Textarea } from '@/components/ui/textarea'
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field'
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
import { FileText, Search } from 'lucide-react'

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

export default function MedicalRecords() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/medical-records?search=${encodeURIComponent(search)}`)

  const [addOpen, setAddOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [viewing, setViewing] = useState(null)
  const [busy, setBusy] = useState(false)

  const [addForm, setAddForm] = useState({
    mdr_pat_name: '',
    mdr_pat_number: '',
    mdr_pat_adr: '',
    mdr_pat_age: '',
    mdr_pat_ailment: '',
    mdr_pat_prescr: '',
  })
  const [editForm, setEditForm] = useState({ mdr_pat_adr: '', mdr_pat_age: '', mdr_pat_ailment: '', mdr_pat_prescr: '' })

  const records = data?.data || []

  const add = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.post('/medical-records', addForm)
      toast.success('Medical record added', {
        description: `${addForm.mdr_pat_name} has a record on file.`,
      })
      setAddOpen(false)
      setAddForm({ mdr_pat_name: '', mdr_pat_number: '', mdr_pat_adr: '', mdr_pat_age: '', mdr_pat_ailment: '', mdr_pat_prescr: '' })
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const openEdit = (r) => {
    setEditing(r)
    setEditForm({
      mdr_pat_adr: r.mdr_pat_adr || '',
      mdr_pat_age: r.mdr_pat_age || '',
      mdr_pat_ailment: r.mdr_pat_ailment || '',
      mdr_pat_prescr: r.mdr_pat_prescr || '',
    })
  }

  const saveEdit = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      // Legacy logic preserved: the original update changed the address,
      // age, prescription and ailment — the record number never changes.
      await api.put(`/medical-records/${editing.mdr_id}`, editForm)
      toast.success('Medical record updated')
      setEditing(null)
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  return (
    <>
      <PageHeader
        title="Medical records"
        description="Patient charts on file — address, age, ailment and the prescription given."
        action={<Button onClick={() => setAddOpen(true)}>Add medical record</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search patient or record number"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-8"
        />
      </div>

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
              <EmptyTitle>Could not load medical records</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Record no.</TableHead>
                <TableHead>Patient</TableHead>
                <TableHead>Ailment</TableHead>
                <TableHead>Prescription</TableHead>
                <TableHead className="text-right">Recorded</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {records.map((r) => (
                <TableRow key={r.mdr_id}>
                  <TableCell><RecordCode value={r.mdr_number} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{r.mdr_pat_name}</p>
                    <p className="mt-0.5 font-mono text-[11px] text-muted-foreground">{r.mdr_pat_number || '–'}</p>
                  </TableCell>
                  <TableCell className="text-muted-foreground">{r.mdr_pat_ailment || '–'}</TableCell>
                  <TableCell className="max-w-[200px] text-muted-foreground">
                    <span className="line-clamp-2 whitespace-pre-line">{r.mdr_pat_prescr || '–'}</span>
                  </TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(r.mdr_date_rec)}</TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-1.5">
                      <Button variant="outline" size="sm" onClick={() => setViewing(r)}>
                        View
                      </Button>
                      <Button variant="outline" size="sm" onClick={() => openEdit(r)}>
                        Edit
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
              {records.length === 0 && (
                <TableRow>
                  <TableCell colSpan={6}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <FileText />
                        </EmptyMedia>
                        <EmptyTitle>No medical records found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No records match the search.' : 'Add the first medical record to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => setAddOpen(true)}>Add medical record</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Add dialog */}
      <Dialog open={addOpen} onOpenChange={setAddOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Add medical record</DialogTitle>
            <DialogDescription>The record number is generated for you when it is saved.</DialogDescription>
          </DialogHeader>
          <form onSubmit={add}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="mdr_pat_name">Patient name</FieldLabel>
                  <Input
                    id="mdr_pat_name"
                    required
                    value={addForm.mdr_pat_name}
                    onChange={(e) => setAddForm({ ...addForm, mdr_pat_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="mdr_pat_number">Patient record no.</FieldLabel>
                  <Input
                    id="mdr_pat_number"
                    placeholder="e.g. 7EW0L"
                    value={addForm.mdr_pat_number}
                    onChange={(e) => setAddForm({ ...addForm, mdr_pat_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="mdr_pat_age">Age</FieldLabel>
                  <Input
                    id="mdr_pat_age"
                    value={addForm.mdr_pat_age}
                    onChange={(e) => setAddForm({ ...addForm, mdr_pat_age: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="mdr_pat_adr">Address</FieldLabel>
                  <Input
                    id="mdr_pat_adr"
                    value={addForm.mdr_pat_adr}
                    onChange={(e) => setAddForm({ ...addForm, mdr_pat_adr: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="mdr_pat_ailment">Ailment</FieldLabel>
                <Input
                  id="mdr_pat_ailment"
                  value={addForm.mdr_pat_ailment}
                  onChange={(e) => setAddForm({ ...addForm, mdr_pat_ailment: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="mdr_pat_prescr">Prescription</FieldLabel>
                <Textarea
                  id="mdr_pat_prescr"
                  rows={3}
                  value={addForm.mdr_pat_prescr}
                  onChange={(e) => setAddForm({ ...addForm, mdr_pat_prescr: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setAddOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Add record'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Edit dialog — legacy fields only: address, age, ailment, prescription */}
      <Dialog open={Boolean(editing)} onOpenChange={(open) => !open && setEditing(null)}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Edit medical record</DialogTitle>
            <DialogDescription>
              {editing?.mdr_pat_name} · record {editing?.mdr_number}
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={saveEdit}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="edit_mdr_pat_age">Age</FieldLabel>
                  <Input
                    id="edit_mdr_pat_age"
                    value={editForm.mdr_pat_age}
                    onChange={(e) => setEditForm({ ...editForm, mdr_pat_age: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="edit_mdr_pat_adr">Address</FieldLabel>
                  <Input
                    id="edit_mdr_pat_adr"
                    value={editForm.mdr_pat_adr}
                    onChange={(e) => setEditForm({ ...editForm, mdr_pat_adr: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="edit_mdr_pat_ailment">Ailment</FieldLabel>
                <Input
                  id="edit_mdr_pat_ailment"
                  value={editForm.mdr_pat_ailment}
                  onChange={(e) => setEditForm({ ...editForm, mdr_pat_ailment: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="edit_mdr_pat_prescr">Prescription</FieldLabel>
                <Textarea
                  id="edit_mdr_pat_prescr"
                  rows={3}
                  value={editForm.mdr_pat_prescr}
                  onChange={(e) => setEditForm({ ...editForm, mdr_pat_prescr: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setEditing(null)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Save changes'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* View dialog */}
      <Dialog open={Boolean(viewing)} onOpenChange={(open) => !open && setViewing(null)}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              Medical record <RecordCode value={viewing?.mdr_number} />
            </DialogTitle>
            <DialogDescription>Saved {fmtDate(viewing?.mdr_date_rec)}</DialogDescription>
          </DialogHeader>
          {viewing && (
            <div className="grid gap-1.5 text-sm">
              <div className="flex justify-between gap-3">
                <span className="text-muted-foreground">Patient</span>
                <span className="font-medium">{viewing.mdr_pat_name}</span>
              </div>
              <div className="flex justify-between gap-3">
                <span className="text-muted-foreground">Record no.</span>
                <RecordCode value={viewing.mdr_pat_number} />
              </div>
              <div className="flex justify-between gap-3">
                <span className="text-muted-foreground">Age</span>
                <span>{viewing.mdr_pat_age || '–'}</span>
              </div>
              <div className="flex justify-between gap-3">
                <span className="text-muted-foreground">Address</span>
                <span>{viewing.mdr_pat_adr || '–'}</span>
              </div>
              <div className="flex justify-between gap-3">
                <span className="text-muted-foreground">Ailment</span>
                <span>{viewing.mdr_pat_ailment || '–'}</span>
              </div>
              {viewing.mdr_pat_prescr && (
                <>
                  <p className="mt-3 text-xs font-medium text-muted-foreground">Prescription</p>
                  <p className="rounded-lg border border-border bg-muted/40 px-3 py-2.5 text-sm leading-relaxed whitespace-pre-line">
                    {viewing.mdr_pat_prescr}
                  </p>
                </>
              )}
            </div>
          )}
        </DialogContent>
      </Dialog>
    </>
  )
}
