<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Issue a real API token for a user, exactly like the login
     * endpoints do, so tests exercise the full auth path including
     * role abilities.
     */
    protected function tokenFor($user, string $role): string
    {
        return $user->createToken('auth', ["role:{$role}"])->plainTextToken;
    }

    /**
     * Reset cached guards between requests. In production each HTTP
     * request boots a fresh app, but a test makes several requests
     * against one app instance and RequestGuard caches the resolved
     * user — without this, the first request's user would leak into
     * every following request.
     */
    protected function freshAuth(): static
    {
        $this->app->make('auth')->forgetGuards();

        return $this;
    }
}
