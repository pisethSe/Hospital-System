import { Component } from 'react'
import { Button } from '@/components/ui/button'
import { CircleAlert } from 'lucide-react'

/*
 * Catches render errors and failed lazy-chunk loads (a stale tab after a
 * redeploy or dev-server restart) and shows a clean, recoverable screen
 * instead of an unmounted white page.
 */
export default class ErrorBoundary extends Component {
  constructor(props) {
    super(props)
    this.state = { error: null }
  }

  static getDerivedStateFromError(error) {
    return { error }
  }

  componentDidCatch(error) {
    // Keep the cause visible in the console for debugging.
    console.error(error)
  }

  render() {
    if (this.state.error) {
      return (
        <div className="flex min-h-screen flex-col items-center justify-center gap-5 bg-background px-4 text-center">
          <span className="flex size-12 items-center justify-center rounded-xl bg-destructive/10 text-destructive">
            <CircleAlert className="size-6" strokeWidth={1.75} aria-hidden="true" />
          </span>
          <div>
            <p className="text-base font-semibold">This page could not be displayed</p>
            <p className="mt-1.5 max-w-sm text-sm leading-relaxed text-muted-foreground">
              The application was updated or the connection was interrupted. Reloading picks up the
              current version.
            </p>
          </div>
          <Button onClick={() => window.location.reload()}>Reload the page</Button>
        </div>
      )
    }

    return this.props.children
  }
}
