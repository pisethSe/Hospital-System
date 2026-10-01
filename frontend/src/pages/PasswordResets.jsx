import { useState } from 'react'
import api from '@/api/client'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
  PageHeader,
  errorMessage,
  useApi,
} from '@/components/ui'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Skeleton } from '@/components/ui/skeleton'
import { toast } from 'sonner'
import { KeyRound, Search } from 'lucide-react'

function fmtDate(value) {
  if (!value) return '–'
  return new Date(value).toLocaleString(undefined, {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  })
}

export default function PasswordResets() {
  const [search, setSearch] = useState('')
  const { data, loading, error, reload } = useApi('/password-resets')

  const [approveFor, setApproveFor] = useState(null)
  const [pwd, setPwd] = useState('')
  const [busy, setBusy] = useState(false)

  const resets = (data?.data || []).filter((r) =>
    (r.email || '').toLowerCase().includes(search.toLowerCase()),
  )

  const approve = async (e) => {
    e.preventDefault()
    setBusy(true)
    try {
      // Legacy logic preserved: the doctor's password is updated by email
      // (hashed with the original algorithm) and the request is marked
      // approved by email.
      await api.post(`/password-resets/${approveFor.id}/approve`, {
        pwd: pwd || undefined,
        status: 'Approved',
      })
      toast.success('Password reset approved', {
        description: `${approveFor.email} can now sign in with the new password.`,
      })
      setApproveFor(null)
      setPwd('')
      reload()
    } catch (err) {
      toast.error(errorMessage(err))
    } finally {
      setBusy(false)
    }
  }

  return (
    <>
      <PageHeader
        title="Password resets"
        description="Reset requests from staff. Approving applies the new password to the doctor's account."
      />

      <div className="relative mt-6 w-full max-w-xs">
        <Search className="pointer-events-none absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
        <Input
          type="search"
          placeholder="Search by email"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="pl-8"
        />
      </div>

      <div className="mt-4 rounded-xl border border-border bg-card">
        {loading ? (
          <div className="flex flex-col gap-2 p-4">
            {Array.from({ length: 4 }).map((_, i) => (
              <Skeleton key={i} className="h-11 w-full" />
            ))}
          </div>
        ) : error ? (
          <Empty>
            <EmptyHeader>
              <EmptyTitle>Could not load reset requests</EmptyTitle>
              <EmptyDescription>{error}</EmptyDescription>
            </EmptyHeader>
          </Empty>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Email</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Requested</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {resets.map((r) => (
                <TableRow key={r.id}>
                  <TableCell className="font-medium">{r.email}</TableCell>
                  <TableCell>
                    <span
                      className={
                        r.status === 'Approved'
                          ? 'text-sm text-success'
                          : 'text-sm font-medium text-warning'
                      }
                    >
                      {r.status}
                    </span>
                  </TableCell>
                  <TableCell className="text-right text-muted-foreground">{fmtDate(r.created_at)}</TableCell>
                  <TableCell className="text-right">
                    {r.status !== 'Approved' && (
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => {
                          setApproveFor(r)
                          setPwd(r.pwd || '')
                        }}
                      >
                        Approve
                      </Button>
                    )}
                  </TableCell>
                </TableRow>
              ))}
              {resets.length === 0 && (
                <TableRow>
                  <TableCell colSpan={4}>
                    <Empty className="border-0">
                      <EmptyHeader>
                        <EmptyMedia variant="icon">
                          <KeyRound />
                        </EmptyMedia>
                        <EmptyTitle>No reset requests</EmptyTitle>
                        <EmptyDescription>
                          {search ? 'No requests match the search.' : 'Nothing to approve right now.'}
                        </EmptyDescription>
                      </EmptyHeader>
                    </Empty>
                  </TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        )}
      </div>

      {/* Approve dialog */}
      <Dialog open={Boolean(approveFor)} onOpenChange={(open) => !open && setApproveFor(null)}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Approve password reset</DialogTitle>
            <DialogDescription>
              {approveFor?.email} will be able to sign in with this password.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={approve}>
            <FieldGroup className="gap-4">
              <Field>
                <FieldLabel htmlFor="reset_pwd">New password</FieldLabel>
                <Input
                  id="reset_pwd"
                  placeholder="Pre-filled with the requested temp password"
                  value={pwd}
                  onChange={(e) => setPwd(e.target.value)}
                />
                <FieldDescription>Leave as-is to apply the temp password from the request.</FieldDescription>
              </Field>
            </FieldGroup>
            <DialogFooter className="mt-6">
              <Button type="button" variant="outline" onClick={() => setApproveFor(null)} disabled={busy}>
                Cancel
              </Button>
              <Button type="submit" disabled={busy}>
                {busy ? 'Approving' : 'Approve reset'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </>
  )
}
