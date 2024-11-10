<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected $guards;

    /**
     * Redirect to the appropriate login page based on guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            // Redirect based on the first guard in the array
            return $this->guards[0] === 'admin' 
                ? route('admin.login') 
                : route('student.login');
        }
    }

    /**
     * Handle the incoming request and set the guard dynamically.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Use the guards passed in the function or fallback to default guard
        $this->guards = $guards ?: ['web'];

        if (!Auth::guard($this->guards[0])->check()) {
            return redirect()->route($this->guards[0] === 'admin' ? 'admin.login' : 'student.login');
        }

        return $next($request);
    }
}
