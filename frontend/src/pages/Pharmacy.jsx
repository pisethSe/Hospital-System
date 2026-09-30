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
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { Pill, Search, Truck } from 'lucide-react'

export default function Pharmacy() {
  const [tab, setTab] = useState('medicines')
  const [search, setSearch] = useState('')

  const medicinesApi = useApi('/pharmaceuticals')
  const categoriesApi = useApi('/pharmaceutical-categories')
  const vendorsApi = useApi('/vendors')

  const [dialogOpen, setDialogOpen] = useState(false) // 'medicine' | 'category' | 'vendor'
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({})

  const reloadAll = () => {
    medicinesApi.reload()
    categoriesApi.reload()
    vendorsApi.reload()
  }

  const openDialog = (kind) => {
    setForm(
      kind === 'medicine'
        ? { phar_name: '', phar_qty: '', phar_cat: '', phar_vendor: '', phar_desc: '' }
        : kind === 'category'
          ? { pharm_cat_name: '', pharm_cat_vendor: '', pharm_cat_desc: '' }
          : { v_name: '', v_adr: '', v_mobile: '', v_email: '', v_phone: '', v_desc: '' },
    )
    setDialogOpen(kind)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      if (dialogOpen === 'medicine') {
        await api.post('/pharmaceuticals', form)
        toast.success('Medicine added', { description: `${form.phar_name} is now in the inventory.` })
      } else if (dialogOpen === 'category') {
        await api.post('/pharmaceutical-categories', form)
        toast.success('Category added')
      } else {
        await api.post('/vendors', form)
        toast.success('Vendor added')
      }
      setDialogOpen(null)
      reloadAll()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const medicines = (medicinesApi.data?.data || []).filter((m) =>
    `${m.phar_name} ${m.phar_bcode} ${m.phar_cat}`.toLowerCase().includes(search.toLowerCase()),
  )
  const categories = (categoriesApi.data?.data || []).filter((c) =>
    `${c.pharm_cat_name} ${c.pharm_cat_vendor}`.toLowerCase().includes(search.toLowerCase()),
  )
  const vendors = (vendorsApi.data?.data || []).filter((v) =>
    `${v.v_name} ${v.v_number}`.toLowerCase().includes(search.toLowerCase()),
  )

  const loading = medicinesApi.loading || categoriesApi.loading || vendorsApi.loading
  const error = medicinesApi.error || categoriesApi.error || vendorsApi.error

  const dialogTitle =
    dialogOpen === 'medicine' ? 'Add medicine' : dialogOpen === 'category' ? 'Add category' : 'Add vendor'
  const dialogDescription =
    dialogOpen === 'medicine'
      ? 'A barcode is generated for you when the medicine is saved.'
      : dialogOpen === 'category'
        ? 'Group medicines under a named category.'
        : 'Vendors supply medicines and equipment. A vendor number is generated for you.'

  return (
    <>
      <PageHeader
        title="Pharmacy"
        description="The stock room — medicines, their categories and the vendors who supply them."
        action={
          <Button onClick={() => openDialog(tab === 'medicines' ? 'medicine' : tab === 'categories' ? 'category' : 'vendor')}>
            {tab === 'medicines' ? 'Add medicine' : tab === 'categories' ? 'Add category' : 'Add vendor'}
          </Button>
        }
      />

      <div className="mt-6 flex flex-wrap items-center justify-between gap-3">
        <Tabs value={tab} onValueChange={setTab}>
          <TabsList>
            <TabsTrigger value="medicines">Medicines</TabsTrigger>
            <TabsTrigger value="categories">Categories</TabsTrigger>
            <TabsTrigger value="vendors">Vendors</TabsTrigger>
          </TabsList>
        </Tabs>
        <div className="relative w-full max-w-xs">
          <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
          <Input
            type="search"
            placeholder="Search"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="pl-8"
          />
        </div>
      </div>

      {loading ? (
        <div className="mt-4 flex flex-col gap-2">
          {Array.from({ length: 6 }).map((_, i) => (
            <Skeleton key={i} className="h-11 w-full rounded-xl" />
          ))}
        </div>
      ) : error ? (
        <Empty className="mt-4">
          <EmptyHeader>
            <EmptyTitle>Could not load the inventory</EmptyTitle>
            <EmptyDescription>{error}</EmptyDescription>
          </EmptyHeader>
        </Empty>
      ) : tab === 'medicines' ? (
        <div className="mt-4 rounded-xl border border-border bg-card">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Barcode</TableHead>
                <TableHead>Medicine</TableHead>
                <TableHead>Category</TableHead>
                <TableHead>Vendor</TableHead>
                <TableHead className="text-right">Qty</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {medicines.map((m) => (
                <TableRow key={m.phar_id}>
                  <TableCell><RecordCode value={m.phar_bcode} /></TableCell>
                  <TableCell>
                    <p className="font-medium">{m.phar_name}</p>
                    {m.phar_desc && (
                      <p className="mt-0.5 max-w-sm truncate text-xs text-muted-foreground">{m.phar_desc}</p>
                    )}
                  </TableCell>
                  <TableCell className="text-muted-foreground">{m.phar_cat || '–'}</TableCell>
                  <TableCell className="text-muted-foreground">{m.phar_vendor || '–'}</TableCell>
                  <TableCell className="text-right tabular-nums text-muted-foreground">{m.phar_qty || '–'}</TableCell>
                </TableRow>
              ))}
              {medicines.length === 0 && (
                <TableRow>
                  <TableCell colSpan={5}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <Pill />
                        </EmptyMedia>
                        <EmptyTitle>No medicines found</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'Nothing in the inventory matches the search.' : 'Add the first medicine to get started.'}
                        </EmptyDescription>
                      </EmptyHeader>
                      {!search && <Button onClick={() => openDialog('medicine')}>Add medicine</Button>}
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        </div>
      ) : tab === 'categories' ? (
        <div className="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {categories.map((c) => (
            <div key={c.pharm_cat_id} className="rounded-xl border border-border bg-card p-5">
              <p className="text-sm font-semibold">{c.pharm_cat_name}</p>
              <p className="mt-1 text-xs text-muted-foreground">Vendor: {c.pharm_cat_vendor || '–'}</p>
              {c.pharm_cat_desc && (
                <p className="mt-2.5 text-sm leading-relaxed text-muted-foreground">{c.pharm_cat_desc}</p>
              )}
            </div>
          ))}
          {categories.length === 0 && (
            <div className="col-span-full">
              <Empty className="border-0">
                <EmptyHeader>
                  <EmptyMedia variant="icon">
                    <Pill />
                  </EmptyMedia>
                  <EmptyTitle>No categories found</EmptyTitle>
                  <EmptyDescription>
                    {search ? 'Nothing matches the search.' : 'Add a category to group medicines.'}
                  </EmptyDescription>
                </EmptyHeader>
                {!search && <Button onClick={() => openDialog('category')}>Add category</Button>}
              </Empty>
            </div>
          )}
        </div>
      ) : (
        <div className="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {vendors.map((v) => (
            <div key={v.v_id} className="rounded-xl border border-border bg-card p-5">
              <div className="flex items-center justify-between gap-3">
                <p className="text-sm font-semibold">{v.v_name}</p>
                <RecordCode value={v.v_number} />
              </div>
              <div className="mt-2.5 flex flex-col gap-0.5 text-sm text-muted-foreground">
                <p>{v.v_adr || 'No address'}</p>
                <p className="text-xs">{v.v_mobile || v.v_phone || 'No phone'}</p>
              </div>
            </div>
          ))}
          {vendors.length === 0 && (
            <div className="col-span-full">
              <Empty className="border-0">
                <EmptyHeader>
                  <EmptyMedia variant="icon">
                    <Truck />
                  </EmptyMedia>
                  <EmptyTitle>No vendors found</EmptyTitle>
                  <EmptyDescription>
                    {search ? 'No vendors match the search.' : 'Add the first vendor to get started.'}
                  </EmptyDescription>
                </EmptyHeader>
                {!search && <Button onClick={() => openDialog('vendor')}>Add vendor</Button>}
              </Empty>
            </div>
          )}
        </div>
      )}

      {/* Add dialog */}
      <Dialog open={Boolean(dialogOpen)} onOpenChange={(open) => !open && setDialogOpen(null)}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{dialogTitle}</DialogTitle>
            <DialogDescription>{dialogDescription}</DialogDescription>
          </DialogHeader>

          <form onSubmit={save}>
            <FieldGroup className="gap-4">
              {dialogOpen === 'medicine' && (
                <>
                  <div className="grid gap-4 sm:grid-cols-2">
                    <Field>
                      <FieldLabel htmlFor="phar_name">Medicine name</FieldLabel>
                      <Input
                        id="phar_name"
                        required
                        value={form.phar_name || ''}
                        onChange={(e) => setForm({ ...form, phar_name: e.target.value })}
                      />
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="phar_qty">Quantity</FieldLabel>
                      <Input
                        id="phar_qty"
                        placeholder="e.g. 200 tablets"
                        value={form.phar_qty || ''}
                        onChange={(e) => setForm({ ...form, phar_qty: e.target.value })}
                      />
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="phar_cat">Category</FieldLabel>
                      <Select value={form.phar_cat || ''} onValueChange={(value) => setForm({ ...form, phar_cat: value })}>
                        <SelectTrigger id="phar_cat" className="w-full">
                          <SelectValue placeholder="No category" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="">No category</SelectItem>
                          {categoriesApi.data?.data?.map((c) => (
                            <SelectItem key={c.pharm_cat_id} value={c.pharm_cat_name}>
                              {c.pharm_cat_name}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="phar_vendor">Vendor</FieldLabel>
                      <Select value={form.phar_vendor || ''} onValueChange={(value) => setForm({ ...form, phar_vendor: value })}>
                        <SelectTrigger id="phar_vendor" className="w-full">
                          <SelectValue placeholder="No vendor" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="">No vendor</SelectItem>
                          {vendorsApi.data?.data?.map((v) => (
                            <SelectItem key={v.v_id} value={v.v_name}>
                              {v.v_name}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </Field>
                  </div>
                  <Field>
                    <FieldLabel htmlFor="phar_desc">Description</FieldLabel>
                    <Textarea
                      id="phar_desc"
                      rows={3}
                      value={form.phar_desc || ''}
                      onChange={(e) => setForm({ ...form, phar_desc: e.target.value })}
                    />
                  </Field>
                </>
              )}

              {dialogOpen === 'category' && (
                <>
                  <Field>
                    <FieldLabel htmlFor="pharm_cat_name">Category name</FieldLabel>
                    <Input
                      id="pharm_cat_name"
                      required
                      value={form.pharm_cat_name || ''}
                      onChange={(e) => setForm({ ...form, pharm_cat_name: e.target.value })}
                    />
                  </Field>
                  <Field>
                    <FieldLabel htmlFor="pharm_cat_vendor">Vendor</FieldLabel>
                    <Select
                      value={form.pharm_cat_vendor || ''}
                      onValueChange={(value) => setForm({ ...form, pharm_cat_vendor: value })}
                    >
                      <SelectTrigger id="pharm_cat_vendor" className="w-full">
                        <SelectValue placeholder="No vendor" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="">No vendor</SelectItem>
                        {vendorsApi.data?.data?.map((v) => (
                          <SelectItem key={v.v_id} value={v.v_name}>
                            {v.v_name}
                          </SelectItem>
                        ))}
                      </SelectContent>
                    </Select>
                  </Field>
                  <Field>
                    <FieldLabel htmlFor="pharm_cat_desc">Description</FieldLabel>
                    <Textarea
                      id="pharm_cat_desc"
                      rows={3}
                      value={form.pharm_cat_desc || ''}
                      onChange={(e) => setForm({ ...form, pharm_cat_desc: e.target.value })}
                    />
                  </Field>
                </>
              )}

              {dialogOpen === 'vendor' && (
                <>
                  <div className="grid gap-4 sm:grid-cols-2">
                    <Field>
                      <FieldLabel htmlFor="v_name">Vendor name</FieldLabel>
                      <Input
                        id="v_name"
                        required
                        value={form.v_name || ''}
                        onChange={(e) => setForm({ ...form, v_name: e.target.value })}
                      />
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="v_adr">Address</FieldLabel>
                      <Input
                        id="v_adr"
                        value={form.v_adr || ''}
                        onChange={(e) => setForm({ ...form, v_adr: e.target.value })}
                      />
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="v_mobile">Mobile</FieldLabel>
                      <Input
                        id="v_mobile"
                        value={form.v_mobile || ''}
                        onChange={(e) => setForm({ ...form, v_mobile: e.target.value })}
                      />
                    </Field>
                    <Field>
                      <FieldLabel htmlFor="v_phone">Phone</FieldLabel>
                      <Input
                        id="v_phone"
                        value={form.v_phone || ''}
                        onChange={(e) => setForm({ ...form, v_phone: e.target.value })}
                      />
                    </Field>
                  </div>
                  <Field>
                    <FieldLabel htmlFor="v_email">Email</FieldLabel>
                    <Input
                      id="v_email"
                      type="email"
                      value={form.v_email || ''}
                      onChange={(e) => setForm({ ...form, v_email: e.target.value })}
                    />
                    <FieldDescription>Used for purchase orders and delivery notes.</FieldDescription>
                  </Field>
                  <Field>
                    <FieldLabel htmlFor="v_desc">Description</FieldLabel>
                    <Textarea
                      id="v_desc"
                      rows={2}
                      value={form.v_desc || ''}
                      onChange={(e) => setForm({ ...form, v_desc: e.target.value })}
                    />
                  </Field>
                </>
              )}
            </FieldGroup>

            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setDialogOpen(null)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Saving' : 'Save'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </>
  )
}
