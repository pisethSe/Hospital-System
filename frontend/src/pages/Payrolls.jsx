import { useState } from 'react'
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
import { BanknoteArrowDown, Search } from 'lucide-react'

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

export default function Payrolls() {
  const { isAdmin } = useAuth()
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/payrolls?search=${encodeURIComponent(search)}`)

  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({
    pay_doc_name: '',
    pay_doc_number: '',
    pay_doc_email: '',
    pay_emp_salary: '',
    pay_descr: '',
    pay_status: 'Pending',
  })

  const [confirm, setConfirm] = useState(null)
  const [confirmBusy, setConfirmBusy] = useState(false)

  const payrolls = data?.data || []

  const openAdd = () => {
    setEditing(null)
    setForm({
      pay_doc_name: '',
      pay_doc_number: '',
      pay_doc_email: '',
      pay_emp_salary: '',
      pay_descr: '',
      pay_status: 'Pending',
    })
    setDialogOpen(true)
  }

  const openEdit = (p) => {
    setEditing(p)
    setForm({
      pay_doc_name: p.pay_doc_name || '',
      pay_doc_number: p.pay_doc_number || '',
      pay_doc_email: p.pay_doc_email || '',
      pay_emp_salary: p.pay_emp_salary || '',
      pay_descr: p.pay_descr || '',
      pay_status: p.pay_status || 'Pending',
    })
    setDialogOpen(true)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (editing) {
        await api.put(`/payrolls/${editing.pay_id}`, form)
        toast.success('Payroll updated')
      } else {
        await api.post('/payrolls', form)
        toast.success('Payroll generated', {
          description: `${form.pay_doc_name}'s payroll is on file.`,
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
      await api.delete(`/payrolls/${confirm.pay_id}`)
      toast.success('Payroll removed')
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
        title="Payroll"
        description="Generated payrolls for doctors and staff."
        action={isAdmin && <Button onClick={openAdd}>Generate payroll</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search staff or payroll number"
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
              <EmptyTitle>Could not load payrolls</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Payroll no.</TableHead>
                <TableHead>Staff</TableHead>
                <TableHead>Salary</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Generated</TableHead>
                {isAdmin && <TableHead className="text-right">Actions</TableHead>}
              </TableRow>
            </TableHeader>
            <TableBody>
              {payrolls.map((p) => (
                <TableRow key={p.pay_id}>
                  <TableCell><RecordCode value={p.pay_number} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{p.pay_doc_name}</p>
                    <p className="mt-0.5 text-xs text-muted-foreground">{p.pay_doc_email || '–'}</p>
                  </TableCell>
                  <TableCell className="tabular-nums text-muted-foreground">{p.pay_emp_salary || '–'}</TableCell>
                  <TableCell><StatusBadge status={p.pay_status} /></TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(p.pay_date_generated)}</TableCell>
                  {isAdmin && (
                    <TableCell className="text-right">
                      <div className="flex justify-end gap-1.5">
                        <Button variant="outline" size="sm" onClick={() => openEdit(p)}>
                          Edit
                        </Button>
                        <Button variant="destructive" size="sm" onClick={() => setConfirm(p)}>
                          Remove
                        </Button>
                      </div>
                    </TableCell>
                  )}
                </TableRow>
              ))}
              {payrolls.length === 0 && (
                <TableRow>
                  <TableCell colSpan={isAdmin ? 6 : 5}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <BanknoteArrowDown />
                        </EmptyMedia>
                        <EmptyTitle>No payrolls found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No payrolls match the search.' : 'Generate the first payroll to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {isAdmin && !search && <Button onClick={openAdd}>Generate payroll</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Generate / edit dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{editing ? 'Edit payroll' : 'Generate payroll'}</DialogTitle>
            <DialogDescription>
              {editing ? 'Update the payroll details and status.' : 'The payroll number is generated for you when it is saved.'}
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="pay_doc_name">Staff name</FieldLabel>
                  <Input
                    id="pay_doc_name"
                    required
                    value={form.pay_doc_name}
                    onChange={(e) => setForm({ ...form, pay_doc_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pay_doc_number">Staff ID</FieldLabel>
                  <Input
                    id="pay_doc_number"
                    value={form.pay_doc_number}
                    onChange={(e) => setForm({ ...form, pay_doc_number: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pay_doc_email">Email</FieldLabel>
                  <Input
                    id="pay_doc_email"
                    type="email"
                    value={form.pay_doc_email}
                    onChange={(e) => setForm({ ...form, pay_doc_email: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="pay_emp_salary">Salary</FieldLabel>
                  <Input
                    id="pay_emp_salary"
                    value={form.pay_emp_salary}
                    onChange={(e) => setForm({ ...form, pay_emp_salary: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="pay_status">Status</FieldLabel>
                <Select value={form.pay_status} onValueChange={(value) => setForm({ ...form, pay_status: value })}>
                  <SelectTrigger id="pay_status" className="w-full">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Pending">Pending</SelectItem>
                    <SelectItem value="Paid">Paid</SelectItem>
                  </SelectContent>
                </Select>
              </Field>
              <Field>
                <FieldLabel htmlFor="pay_descr">Description</FieldLabel>
                <Input
                  id="pay_descr"
                  value={form.pay_descr}
                  onChange={(e) => setForm({ ...form, pay_descr: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : editing ? 'Save changes' : 'Generate payroll'}
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
        title="Remove this payroll?"
        description="The payroll record will be deleted. This cannot be undone."
        confirmLabel="Remove payroll"
        onConfirm={runRemove}
      />
    </>
  )
}
