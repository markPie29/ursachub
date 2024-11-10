<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.login');  // Specify the admin login route if different
        }
    }

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
            return redirect()->route('admin.login'); // Adjust redirect route if needed
        }

        return $next($request);
    }
}
