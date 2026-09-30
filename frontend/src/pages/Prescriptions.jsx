import { useState } from 'react'
import { errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' })
}

export default function Prescriptions() {
  const { data, loading, error, reload } = useApi('/prescriptions')
  const [search, setSearch] = useState('')

  const [addOpen, setAddOpen] = useState(false)
  const [viewing, setViewing] = useState(null)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)

  const [form, setForm] = useState({
    pres_pat_name: '',
    pres_pat_age: '',
    pres_pat_number: '',
    pres_pat_type: 'Outpatient',
    pres_pat_addr: '',
    pres_pat_ailment: '',
    pres_ins: '',
  })
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
    setAlert(null)
    try {
      await api.post('/prescriptions', { ...form, medicines })
      setAddOpen(false)
      setForm({ pres_pat_name: '', pres_pat_age: '', pres_pat_number: '', pres_pat_type: 'Outpatient', pres_pat_addr: '', pres_pat_ailment: '', pres_ins: '' })
      setMedicines([{ name: '', qty: '', time: '' }])
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const view = async (p) => {
    try {
      const res = await api.get(`/prescriptions/${p.pres_id}`)
      setViewing(res.data.data)
    } catch (err) {
      window.alert(errorMessage(err))
    }
  }

  return (
    <>
      <PageHeader
        title="Prescriptions"
        subtitle="Prescriptions with medicines and dosage instructions."
        action={
          <button
            onClick={() => setAddOpen(true)}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            + New prescription
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4">
        <input
          type="search"
          placeholder="Search patient or prescription number…"
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
      ) : (
        <div className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-50">
                <tr className="text-xs uppercase tracking-wide text-slate-500">
                  <th className="px-5 py-3 font-semibold">Prescription #</th>
                  <th className="px-5 py-3 font-semibold">Patient</th>
                  <th className="px-5 py-3 font-semibold">Ailment</th>
                  <th className="px-5 py-3 font-semibold">Medicines</th>
                  <th className="px-5 py-3 font-semibold">Date</th>
                  <th className="px-5 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {prescriptions.map((p) => (
                  <tr key={p.pres_id} className="hover:bg-slate-50">
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">#{p.pres_number}</td>
                    <td className="px-5 py-3.5">
                      <p className="font-medium text-slate-800">{p.pres_pat_name}</p>
                      <p className="text-xs text-slate-500">{p.pres_pat_type || '–'}</p>
                    </td>
                    <td className="px-5 py-3.5 text-slate-600">{p.pres_pat_ailment || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-600">{p.medicines?.length || 0} item(s)</td>
                    <td className="px-5 py-3.5 text-slate-500">{fmtDate(p.pres_date)}</td>
                    <td className="px-5 py-3.5">
                      <div className="flex justify-end">
                        <button
                          onClick={() => view(p)}
                          className="rounded-md bg-brand-50 px-2.5 py-1.5 text-xs font-semibold text-brand-700 hover:bg-brand-100"
                        >
                          View
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {prescriptions.length === 0 && (
                  <tr>
                    <td colSpan={6} className="px-5 py-10 text-center text-slate-500">
                      No prescriptions found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* New prescription */}
      <Modal open={addOpen} onClose={() => setAddOpen(false)} title="New prescription" wide>
        <form onSubmit={save} className="space-y-5">
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Patient name" required>
              <input required value={form.pres_pat_name} onChange={(e) => setForm({ ...form, pres_pat_name: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Patient number">
              <input value={form.pres_pat_number} onChange={(e) => setForm({ ...form, pres_pat_number: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Age">
              <input value={form.pres_pat_age} onChange={(e) => setForm({ ...form, pres_pat_age: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Type">
              <select value={form.pres_pat_type} onChange={(e) => setForm({ ...form, pres_pat_type: e.target.value })} className={inputClass}>
                <option value="Outpatient">Outpatient</option>
                <option value="Inpatient">Inpatient</option>
              </select>
            </Field>
          </div>
          <Field label="Ailment">
            <input value={form.pres_pat_ailment} onChange={(e) => setForm({ ...form, pres_pat_ailment: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Instructions">
            <textarea rows={3} value={form.pres_ins} onChange={(e) => setForm({ ...form, pres_ins: e.target.value })} className={inputClass} />
          </Field>

          <div>
            <div className="mb-2 flex items-center justify-between">
              <p className="text-xs font-semibold uppercase tracking-wide text-slate-500">Medicines</p>
              <button type="button" onClick={addMedicineRow} className="text-xs font-semibold text-brand-600 hover:text-brand-700">
                + Add medicine
              </button>
            </div>
            <div className="space-y-2">
              {medicines.map((m, i) => (
                <div key={i} className="grid grid-cols-[1fr_80px_110px_36px] items-center gap-2">
                  <input required placeholder="Medicine name" value={m.name} onChange={(e) => updateMedicine(i, 'name', e.target.value)} className={inputClass} />
                  <input required placeholder="Qty" value={m.qty} onChange={(e) => updateMedicine(i, 'qty', e.target.value)} className={inputClass} />
                  <input required placeholder="Time" value={m.time} onChange={(e) => updateMedicine(i, 'time', e.target.value)} className={inputClass} />
                  <button
                    type="button"
                    onClick={() => removeMedicineRow(i)}
                    disabled={medicines.length === 1}
                    className="flex h-9 w-9 items-center justify-center rounded-lg text-rose-500 hover:bg-rose-50 disabled:opacity-30"
                  >
                    ×
                  </button>
                </div>
              ))}
            </div>
          </div>

          <p className="text-xs text-slate-500">A prescription number is generated automatically (same algorithm as the legacy system).</p>

          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setAddOpen(false)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Save prescription'}
            </button>
          </div>
        </form>
      </Modal>

      {/* View prescription */}
      <Modal open={Boolean(viewing)} onClose={() => setViewing(null)} title={`Prescription #${viewing?.pres_number || ''}`} wide>
        {viewing && (
          <div className="space-y-4 text-sm">
            <div className="grid gap-2 sm:grid-cols-2">
              <p><span className="text-slate-500">Patient:</span> <span className="font-medium">{viewing.pres_pat_name}</span></p>
              <p><span className="text-slate-500">Number:</span> <span className="font-mono text-xs">{viewing.pres_pat_number}</span></p>
              <p><span className="text-slate-500">Age:</span> {viewing.pres_pat_age || '–'}</p>
              <p><span className="text-slate-500">Type:</span> {viewing.pres_pat_type || '–'}</p>
              <p><span className="text-slate-500">Ailment:</span> {viewing.pres_pat_ailment || '–'}</p>
              <p><span className="text-slate-500">Date:</span> {fmtDate(viewing.pres_date)}</p>
            </div>
            {viewing.pres_ins && (
              <div>
                <p className="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Instructions</p>
                <p className="whitespace-pre-line rounded-lg bg-slate-50 p-3 text-slate-700">{viewing.pres_ins}</p>
              </div>
            )}
            <div>
              <p className="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Medicines</p>
              <table className="w-full text-left text-sm">
                <thead>
                  <tr className="border-b border-slate-200 text-xs uppercase text-slate-500">
                    <th className="py-2 font-semibold">Medicine</th>
                    <th className="py-2 font-semibold">Qty</th>
                    <th className="py-2 font-semibold">Time</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {(viewing.medicines || []).map((m) => (
                    <tr key={m.id}>
                      <td className="py-2 font-medium text-slate-800">{m.medicine_name}</td>
                      <td className="py-2 text-slate-600">{m.medicine_qty}</td>
                      <td className="py-2 text-slate-600">{m.medicine_time}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </Modal>
    </>
  )
}
