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
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { Search, Truck } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  const d = new Date(value)
  return Number.isNaN(d.getTime()) ? value : d.toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  })
}

export default function Transfers() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/transfers?search=${encodeURIComponent(search)}`)

  const [open, setOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({
    t_pat_number: '',
    t_pat_name: '',
    t_date: '',
    t_hospital: '',
    t_status: 'Pending',
  })

  const transfers = data?.data || []

  const transfer = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      // Legacy logic preserved: the transfer row is recorded and the
      // patient's type is set to "Transferred".
      await api.post('/transfers', form)
      toast.success('Patient transferred', {
        description: `${form.t_pat_name} is marked as transferred to ${form.t_hospital || 'the receiving hospital'}.`,
      })
      setOpen(false)
      setForm({ t_pat_number: '', t_pat_name: '', t_date: '', t_hospital: '', t_status: 'Pending' })
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
        title="Transfers"
        description="Patients moved to another hospital. Transferring marks the patient's type as Transferred."
        action={<Button onClick={() => setOpen(true)}>Transfer a patient</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search patient or hospital"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-8"
        />
      </div>

      <div className="mt-4 rounded-xl border border-border bg-card">
        {loading ? (
          <div className="flex flex-col gap-2 p-4">
            {Array.from({ length: 5 }).map((_, i) => (
              <Skeleton key={i} className="h-11 w-full" />
            ))}
          </div>
        ) : error ? (
          <Empty>
            <EmptyHeader>
              <EmptyTitle>Could not load transfers</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Patient</TableHead>
                <TableHead>Record no.</TableHead>
                <TableHead>Receiving hospital</TableHead>
                <TableHead>Date</TableHead>
                <TableHead>Status</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {transfers.map((t) => (
                <TableRow key={t.t_id}>
                  <TableCell className="font-medium">{t.t_pat_name}</TableCell>
                  <TableCell><RecordCode value={t.t_pat_number} /></TableCell>
                  <TableCell className="text-muted-foreground">{t.t_hospital || '–'}</TableCell>
                  <TableCell className="text-muted-foreground">{fmtDate(t.t_date)}</TableCell>
                  <TableCell><StatusBadge status={t.t_status} /></TableCell>
                </TableRow>
              ))}
              {transfers.length === 0 && (
                <TableRow>
                  <TableCell colSpan={5}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <Truck />
                        </EmptyMedia>
                        <EmptyTitle>No transfers found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No transfers match the search.' : 'Transfer the first patient to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => setOpen(true)}>Transfer a patient</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Transfer dialog */}
      <Dialog open={open} onOpenChange={setOpen}>        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Transfer a patient</DialogTitle>
            <DialogDescription>
              The transfer is recorded and the patient is marked as Transferred.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={transfer}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="t_pat_number">Patient record no.</FieldLabel>
                  <Input
                    id="t_pat_number"
                    required
                    placeholder="e.g. 7EW0L"
                    value={form.t_pat_number}
                    onChange={(e) => setForm({ ...form, t_pat_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="t_pat_name">Patient name</FieldLabel>
                  <Input
                    id="t_pat_name"
                    required
                    value={form.t_pat_name}
                    onChange={(e) => setForm({ ...form, t_pat_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="t_hospital">Receiving hospital</FieldLabel>
                  <Input
                    id="t_hospital"
                    placeholder="Hospital name"
                    value={form.t_hospital}
                    onChange={(e) => setForm({ ...form, t_hospital: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="t_date">Transfer date</FieldLabel>
                  <Input
                    id="t_date"
                    type="datetime-local"
                    value={form.t_date}
                    onChange={(e) => setForm({ ...form, t_date: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="t_status">Status</FieldLabel>
                <Input
                  id="t_status"
                  value={form.t_status}
                  onChange={(e) => setForm({ ...form, t_status: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Transferring' : 'Transfer patient'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </>
  )
}
