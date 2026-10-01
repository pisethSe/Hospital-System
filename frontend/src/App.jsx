import { Navigate, Route, Routes } from 'react-router-dom'
import { useAuth } from './context/AuthContext'
import Layout from './components/Layout'
import Landing from './pages/Landing'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
import Patients from './pages/Patients'
import Doctors from './pages/Doctors'
import LabTests from './pages/LabTests'
import Surgeries from './pages/Surgeries'
import Prescriptions from './pages/Prescriptions'
import Pharmacy from './pages/Pharmacy'
import Vitals from './pages/Vitals'
import MedicalRecords from './pages/MedicalRecords'
import Payrolls from './pages/Payrolls'
import Accounts from './pages/Accounts'
import Equipments from './pages/Equipments'
import Transfers from './pages/Transfers'
import PasswordResets from './pages/PasswordResets'
import Profile from './pages/Profile'
import { FullScreenSpinner } from './components/ui'

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
  )
}
