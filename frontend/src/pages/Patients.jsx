import { useState } from 'react'
import { useAuth } from '../context/AuthContext'
import { errorMessage, ErrorAlert, Field, inputClass, Modal, PageHeader, Spinner, useApi } from '../components/ui'
import api from '../api/client'

const emptyForm = {
  pat_fname: '',
  pat_lname: '',
  pat_dob: '',
  pat_age: '',
  pat_phone: '',
  pat_type: 'Outpatient',
  pat_addr: '',
  pat_ailment: '',
  pat_room_number: '',
}

export default function Patients() {
  const { isAdmin } = useAuth()
  const [search, setSearch] = useState('')
  const [status, setStatus] = useState('')
  const [page, setPage] = useState(1)

  const { data, loading, error, reload } = useApi(
    `/patients?search=${encodeURIComponent(search)}&status=${status}&page=${page}&per_page=10`,
  )

  const [modal, setModal] = useState(null) // 'add' | 'edit' | null
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [busy, setBusy] = useState(false)
  const [alert, setAlert] = useState(null)

  const patients = data?.data?.data || []
  const meta = data?.data

  const openAdd = () => {
    setForm(emptyForm)
    setEditing(null)
    setModal('add')
  }

  const openEdit = (p) => {
    setEditing(p)
    setForm({
      pat_fname: p.pat_fname || '',
      pat_lname: p.pat_lname || '',
      pat_dob: p.pat_dob || '',
      pat_age: p.pat_age || '',
      pat_phone: p.pat_phone || '',
      pat_type: p.pat_type || '',
      pat_addr: p.pat_addr || '',
      pat_ailment: p.pat_ailment || '',
      pat_room_number: p.pat_room_number || '',
    })
    setModal('edit')
  }

  const save = async (e) => {
    e.preventDefault()
    setBusy(true)
    setAlert(null)
    try {
      if (editing) {
        await api.put(`/patients/${editing.pat_id}`, form)
      } else {
        await api.post('/patients', form)
      }
      setModal(null)
      reload()
    } catch (err) {
      setAlert(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  const discharge = async (p) => {
    if (!window.confirm(`Discharge ${`${p.pat_fname} ${p.pat_lname}`.trim()}?`)) return
    try {
      await api.post(`/patients/${p.pat_id}/discharge`)
      reload()
    } catch (err) {
      window.alert(errorMessage(err))
    }
  }

  const remove = async (p) => {
    if (!window.confirm(`Delete patient ${p.pat_number}? This cannot be undone.`)) return
    try {
      await api.delete(`/patients/${p.pat_id}`)
      reload()
    } catch (err) {
      window.alert(errorMessage(err))
    }
  }

  return (
    <>
      <PageHeader
        title="Patients"
        subtitle="Registered patients, rooms and discharge records."
        action={
          isAdmin && (
            <button
              onClick={openAdd}
              className="rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500"
            >
              + Register patient
            </button>
          )
        }
      />

      <ErrorAlert message={alert} />

      {/* Filters */}
      <div className="mb-4 flex flex-wrap gap-3">
        <input
          type="search"
          placeholder="Search name, number or ailment…"
          value={search}
          onChange={(e) => {
            setSearch(e.target.value)
            setPage(1)
          }}
          className={`${inputClass} max-w-xs`}
        />
        <select
          value={status}
          onChange={(e) => {
            setStatus(e.target.value)
            setPage(1)
          }}
          className={`${inputClass} max-w-[180px]`}
        >
          <option value="">All patients</option>
          <option value="active">Active only</option>
          <option value="discharged">Discharged only</option>
        </select>
      </div>

      {loading ? (
        <div className="flex justify-center py-16">
          <Spinner className="h-7 w-7" />
        </div>
      ) : error ? (
        <div className="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
          {error}
        </div>
      ) : (
        <div className="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-50">
                <tr className="text-xs uppercase tracking-wide text-slate-500">
                  <th className="px-5 py-3 font-semibold">Patient</th>
                  <th className="px-5 py-3 font-semibold">Number</th>
                  <th className="px-5 py-3 font-semibold">Type</th>
                  <th className="px-5 py-3 font-semibold">Ailment</th>
                  <th className="px-5 py-3 font-semibold">Room</th>
                  <th className="px-5 py-3 font-semibold">Status</th>
                  {isAdmin && <th className="px-5 py-3 text-right font-semibold">Actions</th>}
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {patients.map((p) => {
                  const discharged = Boolean(p.pat_walk_out_date)
                  return (
                    <tr key={p.pat_id} className="hover:bg-slate-50">
                      <td className="px-5 py-3.5">
                        <p className="font-medium text-slate-800">{`${p.pat_fname} ${p.pat_lname}`.trim()}</p>
                        <p className="text-xs text-slate-500">{p.pat_phone || '–'}</p>
                      </td>
                      <td className="px-5 py-3.5 font-mono text-xs text-slate-500">{p.pat_number}</td>
                      <td className="px-5 py-3.5 text-slate-600">{p.pat_type || '–'}</td>
                      <td className="px-5 py-3.5 text-slate-600">{p.pat_ailment || '–'}</td>
                      <td className="px-5 py-3.5 text-slate-600">{p.pat_room_number || '–'}</td>
                      <td className="px-5 py-3.5">
                        <span
                          className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset ${
                            discharged
                              ? 'bg-slate-100 text-slate-600 ring-slate-200'
                              : 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                          }`}
                        >
                          {discharged ? 'Discharged' : p.pat_discharge_status || 'Active'}
                        </span>
                      </td>
                      {isAdmin && (
                        <td className="px-5 py-3.5">
                          <div className="flex justify-end gap-2 text-xs font-semibold">
                            {!discharged && (
                              <button onClick={() => discharge(p)} className="rounded-md bg-slate-100 px-2.5 py-1.5 text-slate-700 hover:bg-slate-200">
                                Discharge
                              </button>
                            )}
                            <button onClick={() => openEdit(p)} className="rounded-md bg-brand-50 px-2.5 py-1.5 text-brand-700 hover:bg-brand-100">
                              Edit
                            </button>
                            <button onClick={() => remove(p)} className="rounded-md bg-rose-50 px-2.5 py-1.5 text-rose-700 hover:bg-rose-100">
                              Delete
                            </button>
                          </div>
                        </td>
                      )}
                    </tr>
                  )
                })}
                {patients.length === 0 && (
                  <tr>
                    <td colSpan={isAdmin ? 7 : 6} className="px-5 py-10 text-center text-slate-500">
                      No patients found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {meta && meta.last_page > 1 && (
            <div className="flex items-center justify-between border-t border-slate-200 px-5 py-3 text-sm">
              <p className="text-slate-500">
                Page {meta.current_page} of {meta.last_page} · {meta.total} patients
              </p>
              <div className="flex gap-2">
                <button
                  disabled={meta.current_page <= 1}
                  onClick={() => setPage((n) => n - 1)}
                  className="rounded-md border border-slate-300 px-3 py-1.5 font-medium text-slate-700 disabled:opacity-40"
                >
                  Previous
                </button>
                <button
                  disabled={meta.current_page >= meta.last_page}
                  onClick={() => setPage((n) => n + 1)}
                  className="rounded-md border border-slate-300 px-3 py-1.5 font-medium text-slate-700 disabled:opacity-40"
                >
                  Next
                </button>
              </div>
            </div>
          )}
        </div>
      )}

      {/* Add / edit modal */}
      <Modal open={modal !== null} onClose={() => setModal(null)} title={editing ? 'Edit patient' : 'Register patient'} wide>
        <form onSubmit={save} className="grid gap-4 sm:grid-cols-2">
          <Field label="First name" required>
            <input required value={form.pat_fname} onChange={(e) => setForm({ ...form, pat_fname: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Last name" required>
            <input required value={form.pat_lname} onChange={(e) => setForm({ ...form, pat_lname: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Phone">
            <input value={form.pat_phone} onChange={(e) => setForm({ ...form, pat_phone: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Type">
            <select value={form.pat_type} onChange={(e) => setForm({ ...form, pat_type: e.target.value })} className={inputClass}>
              <option value="Outpatient">Outpatient</option>
              <option value="Inpatient">Inpatient</option>
            </select>
          </Field>
          <Field label="Date of birth">
            <input type="date" value={form.pat_dob} onChange={(e) => setForm({ ...form, pat_dob: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Age">
            <input value={form.pat_age} onChange={(e) => setForm({ ...form, pat_age: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Ailment">
            <input value={form.pat_ailment} onChange={(e) => setForm({ ...form, pat_ailment: e.target.value })} className={inputClass} />
          </Field>
          <Field label="Room number">
            <input value={form.pat_room_number} onChange={(e) => setForm({ ...form, pat_room_number: e.target.value })} className={inputClass} />
          </Field>
          <div className="sm:col-span-2">
            <Field label="Address">
              <input value={form.pat_addr} onChange={(e) => setForm({ ...form, pat_addr: e.target.value })} className={inputClass} />
            </Field>
          </div>
          <div className="flex justify-end gap-3 sm:col-span-2">
            <button type="button" onClick={() => setModal(null)} className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">
              Cancel
            </button>
            <button
              type="submit"
              disabled={busy}
              className="flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-500 disabled:opacity-60"
            >
              {busy && <Spinner className="h-4 w-4 text-white" />}
              {editing ? 'Save changes' : 'Register patient'}
            </button>
          </div>
        </form>
      </Modal>
    </>
  )
}
