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
import { Textarea } from '@/components/ui/textarea'
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
import { FlaskConical, Search } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    hour: 'numeric',
    minute: '2-digit',
  })
}

export default function LabTests() {
  const [search, setSearch] = useState('')
  const [pendingOnly, setPendingOnly] = useState(false)

  const { data, loading, error, reload } = useApi(
    `/lab-tests?search=${encodeURIComponent(search)}&pending=${pendingOnly}`,
  )

  const [addOpen, setAddOpen] = useState(false)
  const [resultFor, setResultFor] = useState(null)
  const [busy, setBusy] = useState(false)

  const [form, setForm] = useState({
    lab_pat_name: '',
    lab_pat_number: '',
    lab_pat_ailment: '',
    lab_pat_tests: '',
  })
  const [result, setResult] = useState('')

  const tests = data?.data || []

  const addTest = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.post('/lab-tests', form)
      toast.success('Lab test requested', {
        description: `${form.lab_pat_name} was added to the bench queue.`,
      })
      setAddOpen(false)
      setForm({ lab_pat_name: '', lab_pat_number: '', lab_pat_ailment: '', lab_pat_tests: '' })
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const saveResult = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      await api.put(`/lab-tests/${resultFor.lab_id}/result`, { lab_pat_results: result })
      toast.success('Results recorded')
      setResultFor(null)
      setResult('')
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
        title="Laboratory"
        description="Requested panels, recorded results, and what is still waiting on the bench."
        action={<Button onClick={() => setAddOpen(true)}>Request lab test</Button>}
      />

      <div className="mt-6 flex flex-wrap items-center gap-3">
        <div className="relative w-full max-w-xs">
          <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input
            type="search"
            placeholder="Search patient or lab number"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="pl-8"
          />
        </div>
        <label className="flex items-center gap-2 text-sm text-muted-foreground">
          <input
            type="checkbox"
            checked={pendingOnly}
            onChange={(e) => setPendingOnly(e.target.checked)}
            className="size-4 rounded border-input accent-[var(--primary)]"
          />
          Waiting on results only
        </label>
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
              <EmptyTitle>Could not load lab tests</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Lab no.</TableHead>
                <TableHead>Patient</TableHead>
                <TableHead>Tests requested</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Recorded</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {tests.map((l) => (
                <TableRow key={l.lab_id}>
                  <TableCell><RecordCode value={l.lab_number} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{l.lab_pat_name}</p>
                    <p className="mt-0.5 font-mono text-[11px] text-muted-foreground">{l.lab_pat_number || '–'}</p>
                  </TableCell>
                  <TableCell className="max-w-[240px] text-muted-foreground">
                    <span className="line-clamp-2 whitespace-pre-line">{l.lab_pat_tests || '–'}</span>
                  </TableCell>
                  <TableCell><StatusBadge status={l.lab_pat_results ? 'Completed' : 'Pending'} /></TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(l.lab_date_rec)}</TableCell>
                  <TableCell className="text-right">
                    <Button
                      variant="outline"
                      size="sm"
                      onClick={() => {
                        setResultFor(l)
                        setResult(l.lab_pat_results || '')
                      }}
                    >
                      {l.lab_pat_results ? 'Edit results' : 'Record results'}
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
              {tests.length === 0 && (
                <TableRow>
                  <TableCell colSpan={6}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <FlaskConical />
                        </EmptyMedia>
                        <EmptyTitle>No lab tests found</EmptyTitle>
                        <EmptyDescription>
                          {pendingOnly
                            ? 'Nothing is waiting on the bench — every requested panel has results.'
                            : search
                              ? 'No panels match the search.'
                              : 'Request the first test to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!pendingOnly && !search && (
                        <Button onClick={() => setAddOpen(true)}>Request lab test</Button>
                      )}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Request test dialog */}
      <Dialog open={addOpen} onOpenChange={setAddOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Request a lab test</DialogTitle>
            <DialogDescription>
              The panel goes to the bench queue with a lab number generated for you.
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={addTest}>
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="lab_pat_name">Patient name</FieldLabel>
                <Input
                  id="lab_pat_name"
                  required
                  value={form.lab_pat_name}
                  onChange={(e) => setForm({ ...form, lab_pat_name: e.target.value })}
                />
              </Field>
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="lab_pat_number">Patient record no.</FieldLabel>
                  <Input
                    id="lab_pat_number"
                    placeholder="e.g. 7EW0L"
                    value={form.lab_pat_number}
                    onChange={(e) => setForm({ ...form, lab_pat_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="lab_pat_ailment">Ailment</FieldLabel>
                  <Input
                    id="lab_pat_ailment"
                    value={form.lab_pat_ailment}
                    onChange={(e) => setForm({ ...form, lab_pat_ailment: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="lab_pat_tests">Tests requested</FieldLabel>
                <Textarea
                  id="lab_pat_tests"
                  required
                  rows={4}
                  placeholder="e.g. Body temperature, blood, stool, urine"
                  value={form.lab_pat_tests}
                  onChange={(e) => setForm({ ...form, lab_pat_tests: e.target.value })}
                />
                <FieldDescription>One test per line, or a short list in plain words.</FieldDescription>
              </Field>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setAddOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Requesting' : 'Request test'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Record results dialog */}
      <Dialog open={Boolean(resultFor)} onOpenChange={(open) => !open && setResultFor(null)}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>Record results</DialogTitle>
            <DialogDescription>
              {resultFor?.lab_pat_name} · panel {resultFor?.lab_number}
            </DialogDescription>
          </DialogHeader>

          <form onSubmit={saveResult}>
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="lab_pat_results">Results</FieldLabel>
                <Textarea
                  id="lab_pat_results"
                  required
                  rows={6}
                  placeholder="What came back from the panel"
                  value={result}
                  onChange={(e) => setResult(e.target.value)}
                />
              </Field>
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setResultFor(null)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Record results'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </>
  )
}
