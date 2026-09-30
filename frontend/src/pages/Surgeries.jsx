import { useState } from 'react'
import { Badge, errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

export default function Surgeries() {
  const { data, loading, error, reload } = useApi('/surgeries')
  const [search, setSearch] = useState('')

  const [addOpen, setAddOpen] = useState(false)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)

  const [form, setForm] = useState({ s_doc: '', s_pat_name: '', s_pat_number: '', s_pat_ailment: '', s_pat_date: '' })

  const surgeries = (data?.data || []).filter((s) =>
    `${s.s_pat_name} ${s.s_pat_number} ${s.s_number} ${s.s_doc}`.toLowerCase().includes(search.toLowerCase()),
  )

  const add = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      await api.post('/surgeries', form)
      setAddOpen(false)
      setForm({ s_doc: '', s_pat_name: '', s_pat_number: '', s_pat_ailment: '', s_pat_date: '' })
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const setStatus = async (s, status) => {
    try {
      await api.put(`/surgeries/${s.s_id}`, { s_pat_status: status })
      reload()
    } catch (err) {
      window.alert(errorMessage(err))
    }
  }

  return (
    <>
      <PageHeader
        title="Surgery / Theatre"
        subtitle="Theatre patients, assigned surgeons and surgery status."
        action={
          <button
            onClick={() => setAddOpen(true)}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            + Add theatre patient
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4">
        <input
          type="search"
          placeholder="Search patient, surgeon or surgery number…"
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
                  <th className="px-5 py-3 font-semibold">Surgery #</th>
                  <th className="px-5 py-3 font-semibold">Patient</th>
                  <th className="px-5 py-3 font-semibold">Surgeon</th>
                  <th className="px-5 py-3 font-semibold">Ailment</th>
                  <th className="px-5 py-3 font-semibold">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {surgeries.map((s) => (
                  <tr key={s.s_id} className="hover:bg-slate-50">
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">#{s.s_number}</td>
                    <td className="px-5 py-3.5">
                      <p className="font-medium text-slate-800">{s.s_pat_name}</p>
                      <p className="font-mono text-xs text-slate-500">{s.s_pat_number}</p>
                    </td>
                    <td className="px-5 py-3.5 text-slate-600">{s.s_doc || '–'}</td>
                    <td className="px-5 py-3.5 text-slate-600">{s.s_pat_ailment || '–'}</td>
                    <td className="px-5 py-3.5">
                      <select
                        value={s.s_pat_status || 'Pending'}
                        onChange={(e) => setStatus(s, e.target.value)}
                        className="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs font-medium text-slate-700"
                      >
                        <option value="Pending">Pending</option>
                        <option value="Successful">Successful</option>
                        <option value="Failed">Failed</option>
                        <option value="Cancelled">Cancelled</option>
                      </select>
                    </td>
                  </tr>
                ))}
                {surgeries.length === 0 && (
                  <tr>
                    <td colSpan={5} className="px-5 py-10 text-center text-slate-500">
                      No theatre patients found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      <Modal open={addOpen} onClose={() => setAddOpen(false)} title="Add theatre patient">
        <form onSubmit={add} className="space-y-4">
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Patient name" required>
              <input required value={form.s_pat_name} onChange={(e) => setForm({ ...form, s_pat_name: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Patient number" required>
              <input required value={form.s_pat_number} onChange={(e) => setForm({ ...form, s_pat_number: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Surgeon">
              <input value={form.s_doc} onChange={(e) => setForm({ ...form, s_doc: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Surgery date">
              <input type="datetime-local" value={form.s_pat_date} onChange={(e) => setForm({ ...form, s_pat_date: e.target.value })} className={inputClass} />
            </Field>
          </div>
          <Field label="Ailment">
            <input value={form.s_pat_ailment} onChange={(e) => setForm({ ...form, s_pat_ailment: e.target.value })} className={inputClass} />
          </Field>
          <p className="text-xs text-slate-500">A surgery number is generated automatically (same algorithm as the legacy system).</p>
          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setAddOpen(false)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Add theatre patient'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
