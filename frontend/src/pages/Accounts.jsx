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
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { BookOpen, Search } from 'lucide-react'

export default function Accounts() {
  const [tab, setTab] = useState('Payable')
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(
    `/accounts?type=${encodeURIComponent(tab)}&search=${encodeURIComponent(search)}`,
  )

  const [dialogOpen, setDialogOpen] = useState(false)
  const [editing, setEditing] = useState(null)
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({ acc_name: '', acc_desc: '', acc_amount: '' })

  const [confirm, setConfirm] = useState(null)
  const [confirmBusy, setConfirmBusy] = useState(false)

  const accounts = data?.data || []

  const openAdd = () => {
    setEditing(null)
    setForm({ acc_name: '', acc_desc: '', acc_amount: '' })
    setDialogOpen(true)
  }

  const openEdit = (a) => {
    setEditing(a)
    setForm({ acc_name: a.acc_name || '', acc_desc: a.acc_desc || '', acc_amount: a.acc_amount || '' })
    setDialogOpen(true)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (editing) {
        // Legacy logic preserved: name, description and amount are
        // updated — the account number never changes.
        await api.put(`/accounts/${editing.acc_id}`, {
          acc_name: form.acc_name,
          acc_desc: form.acc_desc,
          acc_type: editing.acc_type,
          acc_amount: form.acc_amount,
        })
        toast.success('Account updated')
      } else {
        await api.post('/accounts', { ...form, acc_type: tab })
        toast.success('Account added', {
          description: `${form.acc_name} recorded under ${tab.toLowerCase()} accounts.`,
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
      await api.delete(`/accounts/${confirm.acc_id}`)
      toast.success('Account removed')
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
        title="Accounts"
        description="Money the hospital owes and is owed, split into payable and receivable."
        action={<Button onClick={openAdd}>Add {tab.toLowerCase()} account</Button>}
      />

      <div className="mt-6 flex flex-wrap items-center justify-between gap-3">
        <Tabs
          value={tab}
          onValueChange={(value) => {
            setTab(value)
            setSearch('')
          }}
        >
          <TabsList>
            <TabsTrigger value="Payable">Payable</TabsTrigger>
            <TabsTrigger value="Receivable">Receivable</TabsTrigger>
          </TabsList>
        </Tabs>
        <div className="relative w-full max-w-xs">
          <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input
            type="search"
            placeholder="Search account name or number"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="pl-8"
          />
        </div>
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
              <EmptyTitle>Could not load accounts</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Account no.</TableHead>
                <TableHead>Name</TableHead>
                <TableHead>Description</TableHead>
                <TableHead className="text-right">Amount</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {accounts.map((a) => (
                <TableRow key={a.acc_id}>
                  <TableCell><RecordCode value={a.acc_number} /></TableCell>
                  <TableCell className="font-medium">{a.acc_name}</TableCell>
                  <TableCell className="max-w-[280px] text-muted-foreground">{a.acc_desc || '–'}</TableCell>
                  <TableCell className="text-right tabular-nums">{a.acc_amount || '–'}</TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-1.5">
                      <Button variant="outline" size="sm" onClick={() => openEdit(a)}>
                        Edit
                      </Button>
                      <Button variant="destructive" size="sm" onClick={() => setConfirm(a)}>
                        Remove
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
              {accounts.length === 0 && (
                <TableRow>
                  <TableCell colSpan={5}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <BookOpen />
                        </EmptyMedia>
                        <EmptyTitle>No {tab.toLowerCase()} accounts found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No accounts match the search.' : `Add the first ${tab.toLowerCase()} account to get started.`}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={openAdd}>Add {tab.toLowerCase()} account</Button>}
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
            <DialogTitle>{editing ? 'Edit account' : `Add ${tab.toLowerCase()} account`}</DialogTitle>
            <DialogDescription>
              {editing
                ? 'The account number never changes — only the details do.'
                : 'The account number is generated for you when it is saved.'}
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="acc_name">Account name</FieldLabel>
                <Input
                  id="acc_name"
                  required
                  value={form.acc_name}
                  onChange={(e) => setForm({ ...form, acc_name: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="acc_desc">Description</FieldLabel>
                <Textarea
                  id="acc_desc"
                  rows={2}
                  value={form.acc_desc}
                  onChange={(e) => setForm({ ...form, acc_desc: e.target.value })}
                />
              </Field>
              <Field>
                <FieldLabel htmlFor="acc_amount">Amount</FieldLabel>
                <Input
                  id="acc_amount"
                  value={form.acc_amount}
                  onChange={(e) => setForm({ ...form, acc_amount: e.target.value })}
                />
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : editing ? 'Save changes' : 'Add account'}
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
        title="Remove this account?"
        description="The account record will be deleted. This cannot be undone."
        confirmLabel="Remove account"
        onConfirm={runRemove}
      />
    </>
  )
}
