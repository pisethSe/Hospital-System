import { Link } from 'react-router-dom'
import { useAuth } from '@/context/AuthContext'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyTitle,
  PageHeader,
  RecordCode,
  StatCard,
  StatusBadge,
  useApi,
} from '@/components/ui'
import { Button } from '@/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { Alert, AlertDescription } from '@/components/ui/alert'
import { CircleAlert } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    hour: 'numeric',
    minute: '2-digit',
  })
}

function StatSkeleton() {
  return (
    <Card className="gap-2 py-4">
      <CardHeader>
        <Skeleton className="h-3 w-24" />
      </CardHeader>
      <CardContent>
        <Skeleton className="h-8 w-16" />
      </CardContent>
    </Card>
  )
}

export default function Dashboard() {
  const { user, isAdmin } = useAuth()
  const { data, loading, error } = useApi('/dashboard')

  return (
    <>
      <PageHeader
        title={`Good to see you, ${user?.name?.split(' ')[0] || 'there'}`}
        description={
          isAdmin
            ? 'The hospital at a glance — patients on the books, work on the bench, theatre schedule.'
            : 'Your patients, lab work and prescriptions at a glance.'
        }
      />

      {error && (
        <Alert variant="destructive" className="mt-6">
          <CircleAlert />
          <AlertDescription>{error}</AlertDescription>
        </Alert>
      )}

      {/* Stats */}
      <div className="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {loading ? (
          Array.from({ length: isAdmin ? 8 : 5 }).map((_, i) => <StatSkeleton key={i} />)
        ) : (
          <>
            <StatCard label="Patients on the books" value={data?.stats?.patients} />
            <StatCard label="Currently in the hospital" value={data?.stats?.active_patients} tone="primary" />
            {isAdmin && <StatCard label="Doctors" value={data?.stats?.doctors} />}
            <StatCard label="Lab tests requested" value={data?.stats?.lab_tests} />
            <StatCard
              label="Waiting on results"
              value={data?.stats?.pending_lab_tests}
              tone={data?.stats?.pending_lab_tests > 0 ? 'warning' : 'default'}
            />
            {isAdmin && <StatCard label="Theatre records" value={data?.stats?.surgeries} />}
            <StatCard label="Prescriptions" value={data?.stats?.prescriptions} />
            {isAdmin && <StatCard label="Medicines in stock" value={data?.stats?.medicines} />}
          </>
        )}
      </div>

      {/* Recent patients */}
      <Card className="mt-8">
        <CardHeader className="flex-row items-center justify-between">
          <div>
            <CardTitle>Latest registrations</CardTitle>
            <CardDescription className="mt-1">The most recent patients to join the books.</CardDescription>
          </div>
          <Button variant="outline" size="sm" render={<Link to="./patients" />}>
            All patients
          </Button>
        </CardHeader>
        <CardContent>
          {loading ? (
            <div className="flex flex-col gap-2">
              {Array.from({ length: 4 }).map((_, i) => (
                <Skeleton key={i} className="h-10 w-full" />
              ))}
            </div>
          ) : data?.recent_patients?.length ? (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Patient</TableHead>
                  <TableHead>Record no.</TableHead>
                  <TableHead>Ailment</TableHead>
                  <TableHead className="text-right">Joined</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {data.recent_patients.map((p) => (
                  <TableRow key={p.pat_id}>
                    <TableCell className="font-medium">{`${p.pat_fname} ${p.pat_lname}`.trim()}</TableCell>
                    <TableCell><RecordCode value={p.pat_number} /></TableCell>
                    <TableCell className="text-muted-foreground">{p.pat_ailment || '–'}</TableCell>
                    <TableCell className="text-right text-muted-foreground">{fmtDate(p.pat_date_joined)}</TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          ) : (
            <Empty>
              <EmptyHeader>
                <EmptyTitle>No patients yet</EmptyTitle>
                <EmptyDescription>Register the first patient from the Patients page.</EmptyDescription>
              </EmptyHeader>
            </Empty>
          )}
        </CardContent>
      </Card>

      {/* Lab tests + theatre */}
      <div className={`mt-6 grid gap-6 ${isAdmin ? 'xl:grid-cols-2' : ''}`}>
        <Card>
          <CardHeader className="flex-row items-center justify-between">
            <div>
              <CardTitle>Latest lab tests</CardTitle>
              <CardDescription className="mt-1">Requested panels and whether results are in.</CardDescription>
            </div>
            <Button variant="outline" size="sm" render={<Link to="./lab-tests" />}>
              All lab tests
            </Button>
          </CardHeader>
          <CardContent>
            {loading ? (
              <div className="flex flex-col gap-2">
                {Array.from({ length: 4 }).map((_, i) => (
                  <Skeleton key={i} className="h-10 w-full" />
                ))}
              </div>
            ) : data?.recent_lab_tests?.length ? (
              <div className="flex flex-col divide-y divide-border">
                {data.recent_lab_tests.map((l) => (
                  <div key={l.lab_id} className="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
                    <div className="min-w-0">
                      <p className="truncate text-sm font-medium">{l.lab_pat_name}</p>
                      <p className="mt-0.5 flex items-center gap-2 text-xs text-muted-foreground">
                        <RecordCode value={l.lab_number} />
                        {fmtDate(l.lab_date_rec)}
                      </p>
                    </div>
                    <StatusBadge status={l.lab_pat_results ? 'Completed' : 'Pending'} />
                  </div>
                ))}
              </div>
            ) : (
              <Empty>
                <EmptyHeader>
                  <EmptyTitle>No lab tests yet</EmptyTitle>
                  <EmptyDescription>Request a test from the Laboratory page.</EmptyDescription>
                </EmptyHeader>
              </Empty>
            )}
          </CardContent>
        </Card>

        {isAdmin && (
          <Card>
            <CardHeader className="flex-row items-center justify-between">
              <div>
                <CardTitle>Latest theatre records</CardTitle>
                <CardDescription className="mt-1">Scheduled operations and their surgeons.</CardDescription>
              </div>
              <Button variant="outline" size="sm" render={<Link to="./surgeries" />}>
                All theatre records
              </Button>
            </CardHeader>
            <CardContent>
              {loading ? (
                <div className="flex flex-col gap-2">
                  {Array.from({ length: 4 }).map((_, i) => (
                    <Skeleton key={i} className="h-10 w-full" />
                  ))}
                </div>
              ) : data?.pending_surgeries?.length ? (
                <div className="flex flex-col divide-y divide-border">
                  {data.pending_surgeries.map((s) => (
                    <div key={s.s_id} className="flex items-center justify-between gap-3 py-2.5 first:pt-0 last:pb-0">
                      <div className="min-w-0">
                        <p className="truncate text-sm font-medium">{s.s_pat_name}</p>
                        <p className="mt-0.5 flex items-center gap-2 text-xs text-muted-foreground">
                          <RecordCode value={s.s_number} />
                          Surgeon {s.s_doc || '–'}
                        </p>
                      </div>
                      <StatusBadge status={s.s_pat_status} />
                    </div>
                  ))}
                </div>
              ) : (
                <Empty>
                  <EmptyHeader>
                    <EmptyTitle>No theatre records yet</EmptyTitle>
                    <EmptyDescription>Add a theatre patient from the Surgery page.</EmptyDescription>
                  </EmptyHeader>
                </Empty>
              )}
            </CardContent>
          </Card>
        )}
      </div>
    </>
  )
}
