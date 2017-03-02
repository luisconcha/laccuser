<?php
namespace LaccUser\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use LaccUser\Facade\PermissionReader;

class AuthorizationResource
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle( Request $request, Closure $next )
    {
        $currentAction = \Route::currentRouteAction();
        list( $controller, $action ) = explode( '@', $currentAction );
        $permission = PermissionReader::getPermission( $controller, $action );
        if ( count( $permission ) ) {
            $permission = $permission[ 0 ];
            if ( \Gate::denies( "{$permission['name']}/{$permission['resource_name']}" ) ) {
                throw new AuthorizationException( "Unauthorized User" );
            }
        }

        return $next( $request );
    }
}
