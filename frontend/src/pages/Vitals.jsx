import { useState } from 'react'
import { errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' })
}

export default function Vitals() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi(`/vitals?search=${encodeURIComponent(search)}`)

  const [addOpen, setAddOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)
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
    setAlert(null)
    try {
      await api.post('/vitals', form)
      setAddOpen(false)
      setForm({ vit_pat_number: '', vit_bodytemp: '', vit_heartpulse: '', vit_resprate: '', vit_bloodpress: '' })
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  return (
    <>
      <PageHeader
        title="Patient Vitals"
        subtitle="Body temperature, pulse, respiration rate and blood pressure."
        action={
          <button
            onClick={() => setAddOpen(true)}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            + Add vitals
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4">
        <input
          type="search"
          placeholder="Search by patient number…"
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
                  <th className="px-5 py-3 font-semibold">Vitals #</th>
                  <th className="px-5 py-3 font-semibold">Patient #</th>
                  <th className="px-5 py-3 font-semibold">Body temp</th>
                  <th className="px-5 py-3 font-semibold">Heart pulse</th>
                  <th className="px-5 py-3 font-semibold">Resp. rate</th>
                  <th className="px-5 py-3 font-semibold">Blood press.</th>
                  <th className="px-5 py-3 font-semibold">Recorded</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {vitals.map((v) => (
                  <tr key={v.vit_id} className="hover:bg-slate-50">
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">#{v.vit_number}</td>
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">{v.vit_pat_number}</td>
                    <td className="px-5 py-3.5 text-slate-700">{v.vit_bodytemp || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-700">{v.vit_heartpulse || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-700">{v.vit_resprate || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-700">{v.vit_bloodpress || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-500">{fmtDate(v.vit_daterec)}</td>
                  </tr>
                ))}
                {vitals.length === 0 && (
                  <tr>
                    <td colSpan={7} className="px-5 py-10 text-center text-slate-500">
                      No vitals records found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      <Modal open={addOpen} onClose={() => setAddOpen(false)} title="Add vitals">
        <form onSubmit={save} className="space-y-4">
          <Field label="Patient number" required>
            <input
              required
              value={form.vit_pat_number}
              onChange={(e) => setForm({ ...form, vit_pat_number: e.target.value })}
              placeholder="e.g. 7EW0L"
              className={inputClass}
            />
          </Field>
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Body temperature">
              <input value={form.vit_bodytemp} onChange={(e) => setForm({ ...form, vit_bodytemp: e.target.value })} placeholder="e.g. 36.8 °C" className={inputClass} />
            </Field>
            <Field label="Heart pulse">
              <input value={form.vit_heartpulse} onChange={(e) => setForm({ ...form, vit_heartpulse: e.target.value })} placeholder="e.g. 72 bpm" className={inputClass} />
            </Field>
            <Field label="Respiration rate">
              <input value={form.vit_resprate} onChange={(e) => setForm({ ...form, vit_resprate: e.target.value })} placeholder="e.g. 16 rpm" className={inputClass} />
            </Field>
            <Field label="Blood pressure">
              <input value={form.vit_bloodpress} onChange={(e) => setForm({ ...form, vit_bloodpress: e.target.value })} placeholder="e.g. 120/80" className={inputClass} />
            </Field>
          </div>
          <p className="text-xs text-slate-500">A vitals number is generated automatically (same algorithm as the legacy system).</p>
          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setAddOpen(false)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Save vitals'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
