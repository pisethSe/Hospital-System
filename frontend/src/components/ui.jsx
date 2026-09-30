import { useCallback, useEffect, useState } from 'react'
import api from '@/api/client'
import { Badge } from '@/components/ui/badge'
import { Card, CardContent, CardDescription, CardHeader } from '@/components/ui/card'
import { Spinner } from '@/components/ui/spinner'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'

// Re-export the shadcn empty-state primitives so pages can pull
// everything shared from one place: @/components/ui
export {
  Empty,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
  EmptyDescription,
  EmptyContent,
} from '@/components/ui/empty'

/*
|--------------------------------------------------------------------------
| Shared helpers used across all module pages
|--------------------------------------------------------------------------
*/

/** Simple data-fetching hook: const { data, loading, error, reload } = useApi('/patients') */
export function useApi(url, deps = []) {
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)

  const reload = useCallback(() => {
    setLoading(true)
    setError(null)
    api
      .get(url)
      .then((res) => setData(res.data))
      .catch((err) => setError(err?.response?.data?.message || 'Could not load data.'))
      .finally(() => setLoading(false))
  }, [url])

  useEffect(() => {
    reload()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [url, ...deps])

  return { data, loading, error, reload }
}

export function errorMessage(err, fallback = 'Something went wrong. Please try again.') {
  return err?.response?.data?.message || fallback
}

export function PageHeader({ title, description, action }) {
  return (
    <div className="flex flex-wrap items-end justify-between gap-3">
      <div className="max-w-2xl">
        <h1 className="text-2xl font-semibold tracking-tight">{title}</h1>
        {description && <p className="mt-1 text-sm text-muted-foreground">{description}</p>}
      </div>
      {action}
    </div>
  )
}

export function StatCard({ label, value, hint, tone = 'default' }) {
  const tones = {
    default: 'text-foreground',
    primary: 'text-primary',
    success: 'text-success',
    warning: 'text-warning',
    destructive: 'text-destructive',
  }
  return (
    <Card className="gap-2 py-4">
      <CardHeader>
        <CardDescription className="text-xs font-medium">{label}</CardDescription>
      </CardHeader>
      <CardContent>
        <p className={`text-2xl font-semibold tabular-nums ${tones[tone] || tones.default}`}>{value ?? '–'}</p>
        {hint && <p className="mt-1 text-xs text-muted-foreground">{hint}</p>}
      </CardContent>
    </Card>
  )
}

/*
 * Record codes (patient, lab, surgery, prescription numbers) are the
 * system's vernacular — set them like chart stamps: mono, uppercase,
 * quiet, consistent everywhere.
 */
export function RecordCode({ value }) {
  return (
    <span className="inline-flex items-center rounded-sm border border-border bg-muted/60 px-1.5 py-0.5 font-mono text-[11px] font-medium tracking-wide text-muted-foreground">
      {value || '–'}
    </span>
  )
}

const STATUS_TONES = {
  active: 'border-success/25 text-success',
  success: 'border-success/25 text-success',
  completed: 'border-success/25 text-success',
  discharged: 'border-success/25 text-success',
  pending: 'border-warning/30 text-warning',
  failed: 'border-destructive/30 text-destructive',
  cancelled: 'border-destructive/30 text-destructive',
}

export function StatusBadge({ status }) {
  if (!status) return <span className="text-sm text-muted-foreground">–</span>
  const key = String(status).toLowerCase().replace(/[^a-z]/g, '')
  return <Badge variant="outline" className={STATUS_TONES[key] || 'border-border text-muted-foreground'}>{status}</Badge>
}

/** Confirmation dialog (replaces window.confirm) */
export function ConfirmDialog({
  open,
  onOpenChange,
  title,
  description,
  confirmLabel = 'Confirm',
  destructive = false,
  busy = false,
  onConfirm,
}) {
  return (
    <AlertDialog open={open} onOpenChange={onOpenChange}>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>{title}</AlertDialogTitle>
          {description && <AlertDialogDescription>{description}</AlertDialogDescription>}
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel disabled={busy}>Cancel</AlertDialogCancel>
          <AlertDialogAction
            disabled={busy}
            className={destructive ? 'bg-destructive text-destructive-foreground hover:bg-destructive/90' : ''}
            onClick={(e) => {
              e.preventDefault()
              onConfirm?.()
            }}
          >
            {busy && <Spinner className="size-3.5" />}
            {confirmLabel}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  )
}

export function FullScreenSpinner() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-background">
      <Spinner className="size-6 text-primary" />
    </div>
  )
}
