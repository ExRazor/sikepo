<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = $request->user();

        if($userRole && $userRole->count() > 0)
        {
            $userRole = $userRole->role;
            $checkRole = 0;

            foreach($roles as $role)
            {
                if($userRole == $role && $role =='admin')
                {
                    $checkRole = 1;
                }
                elseif($userRole == $role && $role == 'kajur')
                {
                    $checkRole = 1;
                }
                elseif($userRole == $role && $role == 'kaprodi')
                {
                    $checkRole = 1;
                }
                elseif($userRole == $role && $role == 'dosen')
                {
                    $checkRole = 1;
                }
            }

            if($checkRole == 1)
                return $next($request);
            else
            //    return redirect()->intended(route('dashboard'));
                return abort(404);
        }
        else
        {
            return redirect('/');
        }
    }
}
