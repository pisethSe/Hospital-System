import { lazy, Suspense } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { useAuth } from './context/AuthContext'
import Layout from './components/Layout'
import Landing from './pages/Landing'
import Login from './pages/Login'
import { FullScreenSpinner } from './components/ui'

// Panel pages are split into separate chunks so the landing page and
// sign-in stay small; each module loads on first visit.
const Dashboard = lazy(() => import('./pages/Dashboard'))
const Patients = lazy(() => import('./pages/Patients'))
const Doctors = lazy(() => import('./pages/Doctors'))
const LabTests = lazy(() => import('./pages/LabTests'))
const Surgeries = lazy(() => import('./pages/Surgeries'))
const Prescriptions = lazy(() => import('./pages/Prescriptions'))
const Pharmacy = lazy(() => import('./pages/Pharmacy'))
const Vitals = lazy(() => import('./pages/Vitals'))
const MedicalRecords = lazy(() => import('./pages/MedicalRecords'))
const Payrolls = lazy(() => import('./pages/Payrolls'))
const Accounts = lazy(() => import('./pages/Accounts'))
const Equipments = lazy(() => import('./pages/Equipments'))
const Transfers = lazy(() => import('./pages/Transfers'))
const PasswordResets = lazy(() => import('./pages/PasswordResets'))
const Profile = lazy(() => import('./pages/Profile'))

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
