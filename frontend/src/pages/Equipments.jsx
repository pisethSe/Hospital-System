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
import { MonitorCog, Search } from 'lucide-react'

export default function Equipments() {
  const { isAdmin } = useAuth()
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/equipments?search=${encodeURIComponent(search)}`)

  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({
    eqp_name: '',
    eqp_vendor: '',
    eqp_dept: '',
    eqp_status: 'Active',
    eqp_qty: '',
    eqp_desc: '',
  })

  const [confirm, setConfirm] = useState(null)
  const [confirmBusy, setConfirmBusy] = useState(false)

  const equipments = data?.data || []

  const openAdd = () => {
    setEditing(null)
    setForm({ eqp_name: '', eqp_vendor: '', eqp_dept: '', eqp_status: 'Active', eqp_qty: '', eqp_desc: '' })
    setDialogOpen(true)
  }

  const openEdit = (item) => {
    setEditing(item)
    setForm({
      eqp_name: item.eqp_name || '',
      eqp_vendor: item.eqp_vendor || '',
      eqp_dept: item.eqp_dept || '',
      eqp_status: item.eqp_status || 'Active',
      eqp_qty: item.eqp_qty || '',
      eqp_desc: item.eqp_desc || '',
    })
    setDialogOpen(true)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (editing) {
        // Legacy logic preserved: name, vendor, description, department,
        // status and quantity are updated — the code never changes.
        await api.put(`/equipments/${editing.eqp_id}`, form)
        toast.success('Equipment updated')
      } else {
        await api.post('/equipments', form)
        toast.success('Equipment added', {
          description: `${form.eqp_name} is now in the inventory.`,
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
      await api.delete(`/equipments/${confirm.eqp_id}`)
      toast.success('Equipment removed')
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
        title="Equipment"
        description="The hospital's equipment inventory, with codes, vendors and quantities."
        action={isAdmin && <Button onClick={openAdd}>Add equipment</Button>}
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search equipment, code or vendor"
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
              <EmptyTitle>Could not load equipment</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Code</TableHead>
                <TableHead>Equipment</TableHead>
                <TableHead>Vendor</TableHead>
                <TableHead>Department</TableHead>
                <TableHead>Qty</TableHead>
                <TableHead>Status</TableHead>
                {isAdmin && <TableHead className="text-right">Actions</TableHead>}
              </TableRow>
            </TableHeader>
            <TableBody>
              {equipments.map((item) => (
                <TableRow key={item.eqp_id}>
                  <TableCell><RecordCode value={item.eqp_code} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{item.eqp_name}</p>
                    {item.eqp_desc && (
                      <p className="mt-0.5 max-w-xs truncate text-xs text-muted-foreground">{item.eqp_desc}</p>
                    )}
                  </TableCell>
                  <TableCell className="text-muted-foreground">{item.eqp_vendor || '–'}</TableCell>
                  <TableCell className="text-muted-foreground">{item.eqp_dept || '–'}</TableCell>
                  <TableCell className="tabular-nums text-muted-foreground">{item.eqp_qty || '–'}</TableCell>
                  <TableCell><StatusBadge status={item.eqp_status} /></TableCell>
                  {isAdmin && (
                    <TableCell className="text-right">
                      <div className="flex justify-end gap-1.5">
                        <Button variant="outline" size="sm" onClick={() => openEdit(item)}>
                          Edit
                        </Button>
                        <Button variant="destructive" size="sm" onClick={() => setConfirm(item)}>
                          Remove
                        </Button>
                      </div>
                    </TableCell>
                  )}
                </TableRow>
              ))}
              {equipments.length === 0 && (
                <TableRow>
                  <TableCell colSpan={isAdmin ? 7 : 6}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <MonitorCog />
                        </EmptyMedia>
                        <EmptyTitle>No equipment found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No equipment matches the search.' : 'Add the first equipment to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {isAdmin && !search && <Button onClick={openAdd}>Add equipment</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Add / edit dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{editing ? 'Edit equipment' : 'Add equipment'}</DialogTitle>
            <DialogDescription>
              {editing
                ? 'The equipment code never changes — only the details do.'
                : 'The equipment code is generated for you when it is saved.'}
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <div className="grid gap-4 sm:grid-cols-2">
                <Field>
                  <FieldLabel htmlFor="eqp_name">Equipment name</FieldLabel>
                  <Input
                    id="eqp_name"
                    required
                    value={form.eqp_name}
                    onChange={(e) => setForm({ ...form, eqp_name: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="eqp_vendor">Vendor</FieldLabel>
                  <Input
                    id="eqp_vendor"
                    value={form.eqp_vendor}
                    onChange={(e) => setForm({ ...form, eqp_vendor: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="eqp_dept">Department</FieldLabel>
                  <Input
                    id="eqp_dept"
                    value={form.eqp_dept}
                    onChange={(e) => setForm({ ...form, eqp_dept: e.target.value })}
                  />
                </Field>
                <Field>
                  <FieldLabel htmlFor="eqp_qty">Quantity</FieldLabel>
                  <Input
                    id="eqp_qty"
                    value={form.eqp_qty}
                    onChange={(e) => setForm({ ...form, eqp_qty: e.target.value })}
                  />
                </Field>
              </div>
              <Field>
                <FieldLabel htmlFor="eqp_status">Status</FieldLabel>
                <Input
                  id="eqp_status"
                  value={form.eqp_status}
                  onChange={(e) => setForm({ ...form, eqp_status: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="eqp_desc">Description</FieldLabel>
                <Textarea
                  id="eqp_desc"
                  rows={2}
                  value={form.eqp_desc}
                  onChange={(e) => setForm({ ...form, eqp_desc: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : editing ? 'Save changes' : 'Add equipment'}
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
        title="Remove this equipment?"
        description="The equipment record will be deleted. This cannot be undone."
        confirmLabel="Remove equipment"
        onConfirm={runRemove}
      />
    </>
  )
}
