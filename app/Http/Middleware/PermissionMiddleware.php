<?php

namespace App\Http\Middleware;

use App\Models\Permission;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null,$routes=null)
    {

        if (!auth()->check()) {
            // User is not authenticated, redirect to login or handle as needed.
            return route('login');
        }

        $user = auth()->user();

        $role_has_permissions = Permission::where('role_id', $user->role_id)->pluck('permission')->toArray();

        $role_has_permissions = array_unique($role_has_permissions);

        // dd($permission);
        // exit;

        // validating if the $role_has_permissions array contains the required $permission = (for example 'orders)
        if (in_array($permission,$role_has_permissions)) {
            $permission_has_routes = Permission::where('role_id', $user->role_id)->where('permission',$permission)->pluck('routes')->toArray();
            // dd($permission_has_routes);
            // exit;
            if($routes==null){
                return $next($request);

            }
            else if (in_array($routes, $permission_has_routes)) {
                return $next($request);

            }else{
                abort(403, 'unauthorized access');

            }

        }else{
            abort(403, 'unauthorized access');

        }

        return $next($request);


    }
}
