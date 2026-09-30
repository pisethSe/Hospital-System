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
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
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
import { HeartPulse, Search } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    hour: 'numeric',
    minute: '2-digit',
  })
}

export default function Vitals() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/vitals?search=${encodeURIComponent(search)}`)

  const [addOpen, setAddOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({
    vit_pat_number: '',
    vit_bodytemp: '',
    vit_heartpulse: '',
    vit_resprate: '',
    vit_bloodpress: '',
  })

  const vitals = data?.data || []

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.post('/vitals', form)
      toast.success('Vitals recorded', {
        description: `New observations for patient ${form.vit_pat_number}.`,
      })
      setAddOpen(false)
      setForm({ vit_pat_number: '', vit_bodytemp: '', vit_heartpulse: '', vit_resprate: '', vit_bloodpress: '' })
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
        title="Vitals"
        description="Observations at the bedside — temperature, pulse, respiration and blood pressure."
        action={<Button onClick={() => setAddOpen(true)}>Record vitals</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search by patient record number"
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
              <EmptyTitle>Could not load vitals</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Observation no.</TableHead>
                <TableHead>Patient</TableHead>
                <TableHead>Temp</TableHead>
                <TableHead>Pulse</TableHead>
                <TableHead>Resp</TableHead>
                <TableHead>BP</TableHead>
                <TableHead className="text-right">Recorded</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {vitals.map((v) => (
                <TableRow key={v.vit_id}>
                  <TableCell><RecordCode value={v.vit_number} /></TableCell>
                  <TableCell><RecordCode value={v.vit_pat_number} /></TableCell>
                  <TableCell className="tabular-nums">{v.vit_bodytemp || '–'}</TableCell>
                  <TableCell className="tabular-nums">{v.vit_heartpulse || '–'}</TableCell>
                  <TableCell className="tabular-nums">{v.vit_resprate || '–'}</TableCell>
                  <TableCell className="tabular-nums">{v.vit_bloodpress || '–'}</TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(v.vit_daterec)}</TableCell>
                </TableRow>
              ))}
              {vitals.length === 0 && (
                <TableRow>
                  <TableCell colSpan={7}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <HeartPulse />
                        </EmptyMedia>
                        <EmptyTitle>No observations found</EmptyTitle>
                        <EmptyDescription>
                          {search
                            ? 'No records match the patient number.'
                            : 'Record the first set of vitals to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => setAddOpen(true)}>Record vitals</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Record vitals dialog */}
      <Dialog open={addOpen} onOpenChange={setAddOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Record vitals</DialogTitle>
            <DialogDescription>
              Attach a new set of observations to a patient record number.
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="vit_pat_number">Patient record no.</FieldLabel>
                <Input
                  id="vit_pat_number"
                  required
                  placeholder="e.g. 7EW0L"
                  value={form.vit_pat_number}
                  onChange={(e) => setForm({ ...form, vit_pat_number: e.target.value })}
                />
                <FieldDescription>The number printed on the patient's chart.</FieldDescription>
              </Field>
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="vit_bodytemp">Body temperature</FieldLabel>
                  <Input
                    id="vit_bodytemp"
                    placeholder="36.8 °C"
                    value={form.vit_bodytemp}
                    onChange={(e) => setForm({ ...form, vit_bodytemp: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="vit_heartpulse">Heart pulse</FieldLabel>
                  <Input
                    id="vit_heartpulse"
                    placeholder="72 bpm"
                    value={form.vit_heartpulse}
                    onChange={(e) => setForm({ ...form, vit_heartpulse: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="vit_resprate">Respiration rate</FieldLabel>
                  <Input
                    id="vit_resprate"
                    placeholder="16 rpm"
                    value={form.vit_resprate}
                    onChange={(e) => setForm({ ...form, vit_resprate: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="vit_bloodpress">Blood pressure</FieldLabel>
                  <Input
                    id="vit_bloodpress"
                    placeholder="120/80"
                    value={form.vit_bloodpress}
                    onChange={(e) => setForm({ ...form, vit_bloodpress: e.target.value })}
                  />
                </Field>
              </div>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setAddOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Record vitals'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </>
  )
}
