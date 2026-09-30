import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { Badge, errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

export default function LabTests() {
  const { isAdmin } = useAuth()
  const [search, setSearch] = useState('')
  const [pendingOnly, setPendingOnly] = useState(false)

  const { data, loading, error, reload } = useApi(
    `/lab-tests?search=${encodeURIComponent(search)}&pending=${pendingOnly}`,
  )

  const [addOpen, setAddOpen] = useState(false)
  const [resultFor, setResultFor] = useState(null)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)

  const [form, setForm] = useState({ lab_pat_name: '', lab_pat_number: '', lab_pat_ailment: '', lab_pat_tests: '' })
  const [result, setResult] = useState('')

  const tests = data?.data || []

  const addTest = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      await api.post('/lab-tests', form)
      setAddOpen(false)
      setForm({ lab_pat_name: '', lab_pat_number: '', lab_pat_ailment: '', lab_pat_tests: '' })
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const saveResult = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      await api.put(`/lab-tests/${resultFor.lab_id}/result`, { lab_pat_results: result })
      setResultFor(null)
      setResult('')
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
        title="Laboratory"
        subtitle="Lab test requests and results."
        action={
          <button
            onClick={() => setAddOpen(true)}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            + Request lab test
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4 flex flex-wrap items-center gap-3">
        <input
          type="search"
          placeholder="Search patient or lab number…"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className={`${inputClass} max-w-xs`}
        />
        <label className="flex items-center gap-2 text-sm text-slate-600">
          <input
            type="checkbox"
            checked={pendingOnly}
            onChange={(e) => setPendingOnly(e.target.checked)}
            className="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"
          />
          Pending only
        </label>
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
                  <th className="px-5 py-3 font-semibold">Lab #</th>
                  <th className="px-5 py-3 font-semibold">Patient</th>
                  <th className="px-5 py-3 font-semibold">Patient #</th>
                  <th className="px-5 py-3 font-semibold">Tests</th>
                  <th className="px-5 py-3 font-semibold">Status</th>
                  <th className="px-5 py-3 text-right font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {tests.map((l) => (
                  <tr key={l.lab_id} className="align-top hover:bg-slate-50">
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">#{l.lab_number}</td>
                    <td className="px-5 py-3.5">
                      <p className="font-medium text-slate-800">{l.lab_pat_name}</p>
                      <p className="text-xs text-slate-500">{l.lab_pat_ailment || '–'}</p>
                    </td>
                    <td className="px-5 py-3.5 font-mono text-xs text-slate-500">{l.lab_pat_number}</td>
                    <td className="max-w-[220px] px-5 py-3.5 text-slate-600">
                      <span className="line-clamp-2 whitespace-pre-line">{l.lab_pat_tests || '–'}</span>
                    </td>
                    <td className="px-5 py-3.5">
                      <Badge text={l.lab_pat_results ? 'Completed' : 'Pending'} />
                    </td>
                    <td className="px-5 py-3.5">
                      <div className="flex justify-end">
                        <button
                          onClick={() => {
                            setResultFor(l)
                            setResult(l.lab_pat_results || '')
                          }}
                          className="rounded-md bg-brand-50 px-2.5 py-1.5 text-xs font-semibold text-brand-700 hover:bg-brand-100"
                        >
                          {l.lab_pat_results ? 'Edit results' : 'Add results'}
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
                {tests.length === 0 && (
                  <tr>
                    <td colSpan={6} className="px-5 py-10 text-center text-slate-500">
                      No lab tests found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Add test */}
      <Modal open={addOpen} onClose={() => setAddOpen(false)} title="Request lab test">
        <form onSubmit={addTest} className="space-y-4">
          <Field label="Patient name" required>
            <input required value={form.lab_pat_name} onChange={(e) => setForm({ ...form, lab_pat_name: e.target.value })} className={inputClass} />
          </Field>
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="Patient number">
              <input value={form.lab_pat_number} onChange={(e) => setForm({ ...form, lab_pat_number: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Ailment">
              <input value={form.lab_pat_ailment} onChange={(e) => setForm({ ...form, lab_pat_ailment: e.target.value })} className={inputClass} />
            </Field>
          </div>
          <Field label="Tests requested" required>
            <textarea
              required
              rows={4}
              value={form.lab_pat_tests}
              onChange={(e) => setForm({ ...form, lab_pat_tests: e.target.value })}
              placeholder="e.g. Body temperature, Blood, Stool, Urine"
              className={inputClass}
            />
          </Field>
          <p className="text-xs text-slate-500">A lab number is generated automatically (same algorithm as the legacy system).</p>
          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setAddOpen(false)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Request test'}
            </button>
          </div>
        </form>
      </Modal>

      {/* Add / edit results */}
      <Modal open={Boolean(resultFor)} onClose={() => setResultFor(null)} title={`Lab results — #${resultFor?.lab_number || ''}`}>
        <form onSubmit={saveResult} className="space-y-4">
          <p className="text-sm text-slate-600">
            Patient: <span className="font-medium text-slate-800">{resultFor?.lab_pat_name}</span>
          </p>
          <Field label="Results" required>
            <textarea required rows={6} value={result} onChange={(e) => setResult(e.target.value)} className={inputClass} />
          </Field>
          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setResultFor(null)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button type="submit" disabled={busy} className="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60">
              {busy ? 'Saving…' : 'Save results'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
