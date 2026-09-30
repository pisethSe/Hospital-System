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
import { Separator } from '@/components/ui/separator'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { ClipboardList, Plus, Search, Trash2 } from 'lucide-react'

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

const emptyForm = {
  pres_pat_name: '',
  pres_pat_age: '',
  pres_pat_number: '',
  pres_pat_type: 'Outpatient',
  pres_pat_addr: '',
  pres_pat_ailment: '',
  pres_ins: '',
}

export default function Prescriptions() {
  const { data, loading, error, reload } = useApi('/prescriptions')
  const [search, setSearch] = useState('')

  const [addOpen, setAddOpen] = useState(false)
  const [viewing, setViewing] = useState(null)
  const [busy, setBusy] = useState(false)

  const [form, setForm] = useState(emptyForm)
  const [medicines, setMedicines] = useState([{ name: '', qty: '', time: '' }])

  const prescriptions = (data?.data || []).filter((p) =>
    `${p.pres_pat_name} ${p.pres_pat_number} ${p.pres_number}`.toLowerCase().includes(search.toLowerCase()),
  )

  const addMedicineRow = () => setMedicines([...medicines, { name: '', qty: '', time: '' }])
  const removeMedicineRow = (i) => setMedicines(medicines.filter((_, idx) => i !== idx))
  const updateMedicine = (i, key, value) =>
    setMedicines(medicines.map((m, idx) => (idx === i ? { ...m, [key]: value } : m)))

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.post('/prescriptions', { ...form, medicines })
      toast.success('Prescription saved', {
        description: `${form.pres_pat_name} has a new prescription on file.`,
      })
      setAddOpen(false)
      setForm(emptyForm)
      setMedicines([{ name: '', qty: '', time: '' }])
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const view = async (p) => {
    try {
      const res = await api.get(`/prescriptions/${p.pres_id}`)
      setViewing(res.data.data)
    } catch (err) {
      toast.error(errorMessage(err))
    }
  }

  return (
    <>
      <PageHeader
        title="Prescriptions"
        description="What was prescribed, in what quantity, and how often it is taken."
        action={<Button onClick={() => setAddOpen(true)}>New prescription</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search patient or prescription number"
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
              <EmptyTitle>Could not load prescriptions</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Prescription no.</TableHead>
                <TableHead>Patient</TableHead>
                <TableHead>Ailment</TableHead>
                <TableHead>Medicines</TableHead>
                <TableHead className="text-right">Saved</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {prescriptions.map((p) => (
                <TableRow key={p.pres_id}>
                  <TableCell><RecordCode value={p.pres_number} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{p.pres_pat_name}</p>
                    <p className="mt-0.5 text-xs text-muted-foreground">{p.pres_pat_type || '–'}</p>
                  </TableCell>
                  <TableCell className="text-muted-foreground">{p.pres_pat_ailment || '–'}</TableCell>
                  <TableCell className="text-muted-foreground">{p.medicines?.length || 0} items</TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(p.pres_date)}</TableCell>
                  <TableCell className="text-right">
                    <Button variant="outline" size="sm" onClick={() => view(p)}>
                      View slip
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
              {prescriptions.length === 0 && (
                <TableRow>
                  <TableCell colSpan={6}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <ClipboardList />
                        </EmptyMedia>
                        <EmptyTitle>No prescriptions found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No prescriptions match the search.' : 'Write the first prescription to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => setAddOpen(true)}>New prescription</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* New prescription */}
      <Dialog open={addOpen} onOpenChange={setAddOpen}>
        <DialogContent className="max-h-[90vh] overflow-y-auto sm:max-w-2xl">
          <DialogHeader>
            <DialogTitle>New prescription</DialogTitle>
            <DialogDescription>
              Medicines are stored with the prescription. The number is generated for you.
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="pres_pat_name">Patient name</FieldLabel>
                  <Input
                    id="pres_pat_name"
                    required
                    value={form.pres_pat_name}
                    onChange={(e) => setForm({ ...form, pres_pat_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pres_pat_number">Patient record no.</FieldLabel>
                  <Input
                    id="pres_pat_number"
                    placeholder="e.g. 7EW0L"
                    value={form.pres_pat_number}
                    onChange={(e) => setForm({ ...form, pres_pat_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pres_pat_age">Age</FieldLabel>
                  <Input
                    id="pres_pat_age"
                    value={form.pres_pat_age}
                    onChange={(e) => setForm({ ...form, pres_pat_age: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pres_pat_type">Patient type</FieldLabel>
                  <Select value={form.pres_pat_type} onValueChange={(value) => setForm({ ...form, pres_pat_type: value })}>
                    <SelectTrigger id="pres_pat_type" className="w-full">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Outpatient">Outpatient</SelectItem>
                      <SelectItem value="Inpatient">Inpatient</SelectItem>
                    </SelectContent>
                  </Select>
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="pres_pat_ailment">Ailment</FieldLabel>
                <Input
                  id="pres_pat_ailment"
                  value={form.pres_pat_ailment}
                  onChange={(e) => setForm({ ...form, pres_pat_ailment: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="pres_ins">Instructions</FieldLabel>
                <Textarea
                  id="pres_ins"
                  rows={2}
                  placeholder="Anything the patient or pharmacy should know"
                  value={form.pres_ins}
                  onChange={(e) => setForm({ ...form, pres_ins: e.target.value })}
                />
              </Field>

              {/* Medicines */}
              <div>
                <div className="flex items-center justify-between pb-2">
                  <p className="text-sm font-medium">Medicines</p>
                  <Button type="button" variant="outline" size="sm" onClick={addMedicineRow}>
                    <Plus data-icon="inline-start" />
                    Add medicine
                  </Button>
                </div>
                <div className="flex flex-col gap-2">
                  {medicines.map((m, i) => (
                    <div key={i} className="grid grid-cols-[1fr_72px_100px_32px] items-center gap-2">
                      <Input
                        required
                        placeholder="Medicine name"
                        aria-label={`Medicine ${i + 1} name`}
                        value={m.name}
                        onChange={(e) => updateMedicine(i, 'name', e.target.value)}
                      />
                      <Input
                        required
                        placeholder="Qty"
                        aria-label={`Medicine ${i + 1} quantity`}
                        value={m.qty}
                        onChange={(e) => updateMedicine(i, 'qty', e.target.value)}
                      />
                      <Input
                        required
                        placeholder="Time"
                        aria-label={`Medicine ${i + 1} timing`}
                        value={m.time}
                        onChange={(e) => updateMedicine(i, 'time', e.target.value)}
                      />
                      <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        aria-label={`Remove medicine ${i + 1}`}
                        disabled={medicines.length === 1}
                        onClick={() => removeMedicineRow(i)}
                      >
                        <Trash2 className="text-muted-foreground" />
                      </Button>
                    </div>
                  ))}
                </div>
              </div>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setAddOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Save prescription'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Prescription slip — styled like the paper it replaced */}
      <Dialog open={Boolean(viewing)} onOpenChange={(open) => !open && setViewing(null)}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <span className="text-primary" aria-hidden="true">℞</span>
              Prescription {viewing?.pres_number}
            </DialogTitle>
            <DialogDescription>Saved {fmtDate(viewing?.pres_date)}</DialogDescription>
          </DialogHeader>

          {viewing && (
            <div>
              <div className="grid gap-1.5 text-sm">
                <div className="flex justify-between gap-3">
                  <span className="text-muted-foreground">Patient</span>
                  <span className="font-medium">{viewing.pres_pat_name}</span>
                </div>
                <div className="flex justify-between gap-3">
                  <span className="text-muted-foreground">Record no.</span>
                  <RecordCode value={viewing.pres_pat_number} />
                </div>
                <div className="flex justify-between gap-3">
                  <span className="text-muted-foreground">Age</span>
                  <span>{viewing.pres_pat_age || '–'}</span>
                </div>
                <div className="flex justify-between gap-3">
                  <span className="text-muted-foreground">Type</span>
                  <span>{viewing.pres_pat_type || '–'}</span>
                </div>
                <div className="flex justify-between gap-3">
                  <span className="text-muted-foreground">Ailment</span>
                  <span>{viewing.pres_pat_ailment || '–'}</span>
                </div>
              </div>

              <Separator className="my-4" />

              <p className="pb-2 text-xs font-medium text-muted-foreground">Medicines</p>
              <div className="rounded-lg border border-border">
                {(viewing.medicines || []).map((m, i) => (
                  <div key={m.id ?? i}>
                    {i > 0 && <Separator />}
                    <div className="grid grid-cols-[1fr_auto] items-baseline gap-2 px-3 py-2.5">
                      <div>
                        <p className="text-sm font-medium">{m.medicine_name}</p>
                        <p className="mt-0.5 text-xs text-muted-foreground">Take {m.medicine_qty}, {m.medicine_time}</p>
                      </div>
                    </div>
                  </div>
                ))}
                {!viewing.medicines?.length && (
                  <p className="px-3 py-2.5 text-sm text-muted-foreground">No medicines on this slip.</p>
                )}
              </div>

              {viewing.pres_ins && (
                <>
                  <Separator className="my-4" />
                  <p className="pb-2 text-xs font-medium text-muted-foreground">Instructions</p>
                  <p className="rounded-lg border border-border bg-muted/40 px-3 py-2.5 text-sm leading-relaxed whitespace-pre-line">
                    {viewing.pres_ins}
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
