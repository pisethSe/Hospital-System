import { useState } from 'react'
import { errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

function Tab({ active, onClick, children }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`rounded-lg px-4 py-2 text-sm font-semibold transition ${
        active ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60'
      }`}
    >
      {children}
    </button>
  )
}

export default function Pharmacy() {
  const [tab, setTab] = useState('medicines')
  const [search, setSearch] = useState('')
  const [alert, setAlert] = useState(null)

  const medicinesApi = useApi('/pharmaceuticals')
  const categoriesApi = useApi('/pharmaceutical-categories')
  const vendorsApi = useApi('/vendors')

  const [modal, setModal] = useState(null) // 'medicine' | 'category' | 'vendor'
  const [busy, setBusy] = useState(false)
  const [form, setForm] = useState({})

  const reloadAll = () => {
    medicinesApi.reload()
    categoriesApi.reload()
    vendorsApi.reload()
  }

  const openModal = (kind) => {
    setForm(
      kind === 'medicine'
        ? { phar_name: '', phar_qty: '', phar_cat: '', phar_vendor: '', phar_desc: '' }
        : kind === 'category'
          ? { pharm_cat_name: '', pharm_cat_vendor: '', pharm_cat_desc: '' }
          : { v_name: '', v_adr: '', v_mobile: '', v_email: '', v_phone: '', v_desc: '' },
    )
    setModal(kind)
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      if (modal === 'medicine') await api.post('/pharmaceuticals', form)
      if (modal === 'category') await api.post('/pharmaceutical-categories', form)
      if (modal === 'vendor') await api.post('/vendors', form)
      setModal(null)
      reloadAll()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const medicines = (medicinesApi.data?.data || []).filter((m) =>
    `${m.phar_name} ${m.phar_bcode} ${m.phar_cat}`.toLowerCase().includes(search.toLowerCase()),
  )
  const categories = categoriesApi.data?.data || []
  const vendors = (vendorsApi.data?.data || []).filter((v) =>
    `${v.v_name} ${v.v_number}`.toLowerCase().includes(search.toLowerCase()),
  )

  const loading = medicinesApi.loading || categoriesApi.loading || vendorsApi.loading
  const error = medicinesApi.error || categoriesApi.error || vendorsApi.error

  return (
    <>
      <PageHeader
        title="Pharmacy"
        subtitle="Pharmaceuticals, categories and vendors."
        action={
          <button
            onClick={() => openModal(tab === 'medicines' ? 'medicine' : tab === 'categories' ? 'category' : 'vendor')}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            {tab === 'medicines' ? '+ Add medicine' : tab === 'categories' ? '+ Add category' : '+ Add vendor'}
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div className="flex gap-1.5 rounded-xl bg-slate-200/60 p-1">
          <Tab active={tab === 'medicines'} onClick={() => setTab('medicines')}>Medicines</Tab>
          <Tab active={tab === 'categories'} onClick={() => setTab('categories')}>Categories</Tab>
          <Tab active={tab === 'vendors'} onClick={() => setTab('vendors')}>Vendors</Tab>
        </div>
        <input
          type="search"
          placeholder="Search…"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className={`${inputClass} max-w-xs`}
        />
      </div>

      {loading ? (
        <div className="flex justify-center py-16">
          <Spinner className="h-7 w-7" />
        </div>
      ) : error ? (
        <div className="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">{error}</div>
      ) : tab === 'medicines' ? (
        <div className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-50">
                <tr className="text-xs uppercase tracking-wide text-slate-500">
                  <th className="px-5 py-3 font-semibold">Barcode</th>
                  <th className="px-5 py-3 font-semibold">Medicine</th>
                  <th className="px-5 py-3 font-semibold">Category</th>
                  <th className="px-5 py-3 font-semibold">Vendor</th>
                  <th className="px-5 py-3 font-semibold">Qty</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {medicines.map((m) => (
                  <tr key={m.phar_id} className="hover:bg-slate-50">
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">{m.phar_bcode}</td>
                    <td className="px-5 py-3.5 font-medium text-slate-800">{m.phar_name}</td>
                    <td className="px-5 py-3.5 text-slate-600">{m.phar_cat || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-600">{m.phar_vendor || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-600">{m.phar_qty || '–'}</td>
                  </tr>
                ))}
                {medicines.length === 0 && (
                  <tr>
                    <td colSpan={5} className="px-5 py-10 text-center text-slate-500">No medicines found.</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      ) : tab === 'categories' ? (
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {categories.map((c) => (
            <div key={c.pharm_cat_id} className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
              <p className="font-semibold text-slate-800">{c.pharm_cat_name}</p>
              <p className="mt-1 text-xs text-slate-500">Vendor: {c.pharm_cat_vendor || '–'}</p>
              {c.pharm_cat_desc && <p className="mt-2 line-clamp-2 text-sm text-slate-600">{c.pharm_cat_desc}</p>}
            </div>
          ))}
          {categories.length === 0 && (
            <div className="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
              No categories found.
            </div>
          )}
        </div>
      ) : (
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {vendors.map((v) => (
            <div key={v.v_id} className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
              <div className="flex items-center justify-between">
                <p className="font-semibold text-slate-800">{v.v_name}</p>
                <span className="font-mono text-xs text-slate-400">#{v.v_number}</span>
              </div>
              <dl className="mt-3 space-y-1 text-sm text-slate-600">
                <p>{v.v_adr || '–'}</p>
                <p className="text-xs text-slate-500">{v.v_mobile || v.v_phone || '–'} · {v.v_email || '–'}</p>
              </dl>
            </div>
          ))}
          {vendors.length === 0 && (
            <div className="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
              No vendors found.
            </div>
          )}
        </div>
      )}

      {/* Add modals */}
      <Modal
        open={modal !== null}
        onClose={() => setModal(null)}
        title={modal === 'medicine' ? 'Add medicine' : modal === 'category' ? 'Add category' : 'Add vendor'}
      >
        <form onSubmit={save} className="space-y-4">
          {modal === 'medicine' && (
            <>
              <div className="grid gap-4 sm:grid-cols-2">
                <Field label="Medicine name" required>
                  <input required value={form.phar_name || ''} onChange={(e) => setForm({ ...form, phar_name: e.target.value })} className={inputClass} />
                </Field>
                <Field label="Quantity">
                  <input value={form.phar_qty || ''} onChange={(e) => setForm({ ...form, phar_qty: e.target.value })} className={inputClass} />
                </Field>
                <Field label="Category">
                  <select value={form.phar_cat || ''} onChange={(e) => setForm({ ...form, phar_cat: e.target.value })} className={inputClass}>
                    <option value="">– none –</option>
                    {categories.map((c) => (
                      <option key={c.pharm_cat_id} value={c.pharm_cat_name}>{c.pharm_cat_name}</option>
                    ))}
                  </select>
                </Field>
                <Field label="Vendor">
                  <select value={form.phar_vendor || ''} onChange={(e) => setForm({ ...form, phar_vendor: e.target.value })} className={inputClass}>
                    <option value="">– none –</option>
                    {vendors.map((v) => (
                      <option key={v.v_id} value={v.v_name}>{v.v_name}</option>
                    ))}
                  </select>
                </Field>
              </div>
              <Field label="Description">
                <textarea rows={3} value={form.phar_desc || ''} onChange={(e) => setForm({ ...form, phar_desc: e.target.value })} className={inputClass} />
              </Field>
              <p className="text-xs text-slate-500">A barcode is generated automatically (same algorithm as the legacy system).</p>
            </>
          )}

          {modal === 'category' && (
            <>
              <Field label="Category name" required>
                <input required value={form.pharm_cat_name || ''} onChange={(e) => setForm({ ...form, pharm_cat_name: e.target.value })} className={inputClass} />
              </Field>
              <Field label="Vendor">
                <select value={form.pharm_cat_vendor || ''} onChange={(e) => setForm({ ...form, pharm_cat_vendor: e.target.value })} className={inputClass}>
                  <option value="">– none –</option>
                  {vendors.map((v) => (
                    <option key={v.v_id} value={v.v_name}>{v.v_name}</option>
                  ))}
                </select>
              </Field>
              <Field label="Description">
                <textarea rows={3} value={form.pharm_cat_desc || ''} onChange={(e) => setForm({ ...form, pharm_cat_desc: e.target.value })} className={inputClass} />
              </Field>
            </>
          )}

          {modal === 'vendor' && (
            <>
              <div className="grid gap-4 sm:grid-cols-2">
                <Field label="Vendor name" required>
                  <input required value={form.v_name || ''} onChange={(e) => setForm({ ...form, v_name: e.target.value })} className={inputClass} />
                </Field>
                <Field label="Address">
                  <input value={form.v_adr || ''} onChange={(e) => setForm({ ...form, v_adr: e.target.value })} className={inputClass} />
                </Field>
                <Field label="Mobile">
                  <input value={form.v_mobile || ''} onChange={(e) => setForm({ ...form, v_mobile: e.target.value })} className={inputClass} />
                </Field>
                <Field label="Phone">
                  <input value={form.v_phone || ''} onChange={(e) => setForm({ ...form, v_phone: e.target.value })} className={inputClass} />
                </Field>
              </div>
              <Field label="Email">
                <input type="email" value={form.v_email || ''} onChange={(e) => setForm({ ...form, v_email: e.target.value })} className={inputClass} />
              </Field>
              <Field label="Description">
                <textarea rows={2} value={form.v_desc || ''} onChange={(e) => setForm({ ...form, v_desc: e.target.value })} className={inputClass} />
              </Field>
              <p className="text-xs text-slate-500">A vendor number is generated automatically (same algorithm as the legacy system).</p>
            </>
          )}

          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setModal(null)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Save'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
