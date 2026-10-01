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
  StatusBadge,
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
import { Search, Syringe } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    hour: 'numeric',
    minute: '2-digit',
  })
}

const STATUSES = ['Pending', 'Successful', 'Failed', 'Cancelled']

export default function Surgeries() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi('/surgeries')

  const [addOpen, setAddOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const [confirm, setConfirm] = useState(null)
  const [confirmBusy, setConfirmBusy] = useState(false)
  const [form, setForm] = useState({
    s_doc: '',
    s_pat_name: '',
    s_pat_number: '',
    s_pat_ailment: '',
    s_pat_date: '',
  })

  const surgeries = (data?.data || []).filter((s) =>
    `${s.s_pat_name} ${s.s_pat_number} ${s.s_number} ${s.s_doc}`.toLowerCase().includes(search.toLowerCase()),
  )

  const add = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.post('/surgeries', form)
      toast.success('Theatre patient added', {
        description: `${form.s_pat_name} is on the theatre schedule.`,
      })
      setAddOpen(false)
      setForm({ s_doc: '', s_pat_name: '', s_pat_number: '', s_pat_ailment: '', s_pat_date: '' })
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const setStatus = async (s, status) => {
    try {
      await api.put(`/surgeries/${s.s_id}`, { s_pat_status: status })
      toast.success(`Marked ${status.toLowerCase()}`, {
        description: `${s.s_pat_name}, operation ${s.s_number}.`,
      })
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    }
  }

  const runRemove = async () => {
    if (!confirm) return
    setConfirmBusy(true)
    try {
      await api.delete(`/surgeries/${confirm.s_id}`)
      toast.success('Theatre record removed')
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
        title="Surgery"
        description="The theatre schedule — who is operating, on whom, and where each operation stands."
        action={<Button onClick={() => setAddOpen(true)}>Add theatre patient</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search patient, surgeon or operation number"
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
              <EmptyTitle>Could not load theatre records</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Operation no.</TableHead>
                <TableHead>Patient</TableHead>
                <TableHead>Surgeon</TableHead>
                <TableHead>Ailment</TableHead>
                <TableHead>Scheduled</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {surgeries.map((s) => (
                <TableRow key={s.s_id}>
                  <TableCell><RecordCode value={s.s_number} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{s.s_pat_name}</p>
                    <p className="mt-0.5 font-mono text-[11px] text-muted-foreground">{s.s_pat_number}</p>
                  </TableCell>
                  <TableCell className="text-muted-foreground">{s.s_doc || 'Unassigned'}</TableCell>
                  <TableCell className="text-muted-foreground">{s.s_pat_ailment || '–'}</TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(s.s_pat_date)}</TableCell>
                  <TableCell>
                    <Select
                      value={s.s_pat_status || 'Pending'}
                      onValueChange={(value) => setStatus(s, value)}
                    >
                      <SelectTrigger size="sm" className="w-32" aria-label={`Status for ${s.s_pat_name}`}>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        {STATUSES.map((status) => (
                          <SelectItem key={status} value={status}>{status}</SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </TableCell>
                  <TableCell className="text-right">
                    <Button
                      variant="destructive"
                      size="sm"
                      onClick={() => setConfirm(s)}
                    >
                      Remove
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
              {surgeries.length === 0 && (
                <TableRow>
                  <TableCell colSpan={7}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <Syringe />
                        </EmptyMedia>
                        <EmptyTitle>No theatre records found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No records match the search.' : 'Add the first theatre patient to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => setAddOpen(true)}>Add theatre patient</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Add theatre patient */}
      <Dialog open={addOpen} onOpenChange={setAddOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Add theatre patient</DialogTitle>
            <DialogDescription>
              The operation gets a number automatically and starts as pending.
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={add}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="s_pat_name">Patient name</FieldLabel>
                  <Input
                    id="s_pat_name"
                    required
                    value={form.s_pat_name}
                    onChange={(e) => setForm({ ...form, s_pat_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="s_pat_number">Patient record no.</FieldLabel>
                  <Input
                    id="s_pat_number"
                    required
                    placeholder="e.g. 7EW0L"
                    value={form.s_pat_number}
                    onChange={(e) => setForm({ ...form, s_pat_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="s_doc">Surgeon</FieldLabel>
                  <Input
                    id="s_doc"
                    placeholder="Doctor name"
                    value={form.s_doc}
                    onChange={(e) => setForm({ ...form, s_doc: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="s_pat_date">Scheduled for</FieldLabel>
                  <Input
                    id="s_pat_date"
                    type="datetime-local"
                    value={form.s_pat_date}
                    onChange={(e) => setForm({ ...form, s_pat_date: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="s_pat_ailment">Ailment</FieldLabel>
                <Input
                  id="s_pat_ailment"
                  value={form.s_pat_ailment}
                  onChange={(e) => setForm({ ...form, s_pat_ailment: e.target.value })}
                />
              </Field>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setAddOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Adding' : 'Add theatre patient'}
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
        title="Remove this theatre record?"
        description="The surgery record will be deleted. This cannot be undone."
        confirmLabel="Remove record"
        onConfirm={runRemove}
      />
    </>
  )
}
