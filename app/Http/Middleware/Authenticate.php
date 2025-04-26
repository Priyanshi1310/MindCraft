<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        // Return null if the request expects JSON (API request)
        if (! $request->expectsJson()) {
            return null;
        }

        return null; // Optional, makes intent super clear
    }
}
