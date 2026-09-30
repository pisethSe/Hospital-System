import { useState } from 'react'
import { errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

const emptyForm = {
  doc_fname: '',
  doc_lname: '',
  doc_email: '',
  doc_dept: '',
  doc_number: '',
  password: '',
}

export default function Doctors() {
  const { data, loading, error, reload } = useApi('/doctors')
  const [search, setSearch] = useState('')

  const [modal, setModal] = useState(null)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)

  const doctors = (data?.data || []).filter((d) =>
    `${d.doc_fname} ${d.doc_lname} ${d.doc_number} ${d.doc_dept}`.toLowerCase().includes(search.toLowerCase()),
  )

  const openAdd = () => {
    setForm(emptyForm)
    setEditing(null)
    setModal('add')
  }

  const openEdit = (d) => {
    setEditing(d)
    setForm({
      doc_fname: d.doc_fname || '',
      doc_lname: d.doc_lname || '',
      doc_email: d.doc_email || '',
      doc_dept: d.doc_dept || '',
      doc_number: d.doc_number || '',
      password: '',
    })
    setModal('edit')
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      if (editing) {
        const payload = { ...form }
        if (!payload.password) delete payload.password
        await api.put(`/doctors/${editing.doc_id}`, payload)
      } else {
        await api.post('/doctors', form)
      }
      setModal(null)
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const remove = async (d) => {
    if (!window.confirm(`Remove ${`${d.doc_fname} ${d.doc_lname}`.trim()}?`)) return
    try {
      await api.delete(`/doctors/${d.doc_id}`)
      reload()
    } catch (err) {
      window.alert(errorMessage(err))
    }
  }

  return (
    <>
      <PageHeader
        title="Doctors"
        subtitle="Doctor accounts and departments. Doctors sign in with their doctor ID."
        action={
          <button
            onClick={openAdd}
            className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
          >
            + Add doctor
          </button>
        }
      />

      <ErrorAlert message={alert} />

      <div className="mb-4">
        <input
          type="search"
          placeholder="Search doctors…"
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
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
          {doctors.map((d) => (
            <div key={d.doc_id} className="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
              <div className="flex items-start justify-between">
                <div className="flex items-center gap-3">
                  <span className="flex h-11 w-11 items-center justify-center rounded-full bg-brand-500/10 text-base font-bold text-brand-700">
                    {`${d.doc_fname?.[0] || ''}${d.doc_lname?.[0] || ''}`.toUpperCase() || 'D'}
                  </span>
                  <div>
                    <p className="font-semibold text-slate-800">{`${d.doc_fname} ${d.doc_lname}`.trim()}</p>
                    <p className="text-xs text-slate-500">{d.doc_dept || 'No department'}</p>
                  </div>
                </div>
              </div>
              <dl className="mt-4 space-y-1.5 text-sm">
                <div className="flex justify-between gap-3">
                  <dt className="text-slate-500">Doctor ID</dt>
                  <dd className="font-mono text-xs text-slate-700">{d.doc_number}</dd>
                </div>
                <div className="flex justify-between gap-3">
                  <dt className="text-slate-500">Email</dt>
                  <dd className="truncate text-slate-700">{d.doc_email || '–'}</dd>
                </div>
              </dl>
              <div className="mt-4 flex justify-end gap-2 text-xs font-semibold">
                <button onClick={() => openEdit(d)} className="rounded-md bg-brand-50 px-2.5 py-1.5 text-brand-700 hover:bg-brand-100">
                  Edit
                </button>
                <button onClick={() => remove(d)} className="rounded-md bg-rose-50 px-2.5 py-1.5 text-rose-700 hover:bg-rose-100">
                  Remove
                </button>
              </div>
            </div>
          ))}
          {doctors.length === 0 && (
            <div className="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
              No doctors found.
            </div>
          )}
        </div>
      )}

      <Modal open={modal !== null} onClose={() => setModal(null)} title={editing ? 'Edit doctor' : 'Add doctor'}>
        <form onSubmit={save} className="space-y-4">
          <div className="grid gap-4 sm:grid-cols-2">
            <Field label="First name" required>
              <input required value={form.doc_fname} onChange={(e) => setForm({ ...form, doc_fname: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Last name">
              <input value={form.doc_lname} onChange={(e) => setForm({ ...form, doc_lname: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Email">
              <input type="email" value={form.doc_email} onChange={(e) => setForm({ ...form, doc_email: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Department">
              <input value={form.doc_dept} onChange={(e) => setForm({ ...form, doc_dept: e.target.value })} className={inputClass} />
            </Field>
            <Field label="Doctor ID (login)">
              <input
                value={form.doc_number}
                onChange={(e) => setForm({ ...form, doc_number: e.target.value })}
                placeholder="auto-generated if empty"
                className={inputClass}
              />
            </Field>
            <Field label={editing ? 'New password (optional)' : 'Password'} required={!editing}>
              <input
                type="password"
                required={!editing}
                value={form.password}
                onChange={(e) => setForm({ ...form, password: e.target.value })}
                className={inputClass}
              />
            </Field>
          </div>
          <div className="flex justify-end gap-3">
            <button type="button" onClick={() => setModal(null)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button
              type="submit"
              disabled={busy}
              className="flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60"
            >
              {busy && <Spinner className="h-4 w-4 text-white" />}
              {editing ? 'Save changes' : 'Add doctor'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
