import { lazy, Suspense } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { useAuth } from './context/AuthContext'
import Layout from './components/Layout'
import Landing from './pages/Landing'
import Login from './pages/Login'
import { FullScreenSpinner } from './components/ui'

/*
 * Panel pages are split into separate chunks so the landing page and
 * sign-in stay small; each module loads on first visit.
 *
 * A failed module fetch (dev server restart or a redeploy under an open
 * tab) is retried once — a fresh fetch picks up the current files. If it
 * still fails, the error boundary shows a clean reload screen instead of
 * unmounting the app into a white screen.
 */
function lazyPage(loader) {
  return lazy(() =>
    loader().catch((error) =>
      loader().catch(() => {
        throw error
      }),
    ),
  )
}

const Dashboard = lazyPage(() => import('./pages/Dashboard'))
const Patients = lazyPage(() => import('./pages/Patients'))
const Doctors = lazyPage(() => import('./pages/Doctors'))
const LabTests = lazyPage(() => import('./pages/LabTests'))
const Surgeries = lazyPage(() => import('./pages/Surgeries'))
const Prescriptions = lazyPage(() => import('./pages/Prescriptions'))
const Pharmacy = lazyPage(() => import('./pages/Pharmacy'))
const Vitals = lazyPage(() => import('./pages/Vitals'))
const MedicalRecords = lazyPage(() => import('./pages/MedicalRecords'))
const Payrolls = lazyPage(() => import('./pages/Payrolls'))
const Accounts = lazyPage(() => import('./pages/Accounts'))
const Equipments = lazyPage(() => import('./pages/Equipments'))
const Transfers = lazyPage(() => import('./pages/Transfers'))
const PasswordResets = lazyPage(() => import('./pages/PasswordResets'))
const Profile = lazyPage(() => import('./pages/Profile'))

function ProtectedRoute({ role, children }) {
  const { user, loading } = useAuth()

  if (loading) return <FullScreenSpinner />
  if (!user) return <Navigate to="/login" replace />
  if (role && user.role !== role) {
    return <Navigate to={user.role === 'admin' ? '/admin' : '/doctor'} replace />
  }
  return children
}

export default function App() {
  return (
    <Suspense fallback={<FullScreenSpinner />}>
      <Routes>
          {/* Public */}
          <Route path="/" element={<Landing />} />
          <Route path="/login" element={<Login />} />

          {/* Admin panel (legacy his_admin accounts) */}
          <Route
            path="/admin"
            element={
              <ProtectedRoute role="admin">
                <Layout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Dashboard />} />
            <Route path="patients" element={<Patients />} />
            <Route path="doctors" element={<Doctors />} />
            <Route path="lab-tests" element={<LabTests />} />
            <Route path="surgeries" element={<Surgeries />} />
            <Route path="prescriptions" element={<Prescriptions />} />
            <Route path="pharmacy" element={<Pharmacy />} />
            <Route path="vitals" element={<Vitals />} />
            <Route path="medical-records" element={<MedicalRecords />} />
            <Route path="transfers" element={<Transfers />} />
            <Route path="payrolls" element={<Payrolls />} />
            <Route path="accounts" element={<Accounts />} />
            <Route path="equipments" element={<Equipments />} />
            <Route path="password-resets" element={<PasswordResets />} />
            <Route path="profile" element={<Profile />} />
          </Route>

          {/* Doctor panel (legacy his_docs accounts, login by doctor ID) */}
          <Route
            path="/doctor"
            element={
              <ProtectedRoute role="doctor">
                <Layout />
              </ProtectedRoute>
            }
          >
            <Route index element={<Dashboard />} />
            <Route path="patients" element={<Patients />} />
            <Route path="lab-tests" element={<LabTests />} />
            <Route path="prescriptions" element={<Prescriptions />} />
            <Route path="vitals" element={<Vitals />} />
            <Route path="transfers" element={<Transfers />} />
            <Route path="payrolls" element={<Payrolls />} />
            <Route path="equipments" element={<Equipments />} />
            <Route path="profile" element={<Profile />} />
          </Route>

          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </Suspense>
  )
}
