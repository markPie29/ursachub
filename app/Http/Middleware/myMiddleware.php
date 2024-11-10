<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomAuthMiddleware
{
    protected $guards;

    public function __construct()
    {
        // Set a default guard here if necessary
        $this->guards = ['web'];
    }

    public function handle($request, Closure $next, ...$guards)
    {
        // Use the guards passed in the function or fallback to the default guards
        $this->guards = $guards ?: $this->guards;

        if (!Auth::guard($this->guards[0])->check()) {
            return redirect()->route('login'); // Adjust redirect route if needed
        }

        return $next($request);
    }
}
