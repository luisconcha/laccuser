<?php
namespace LaccUser\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaccUser\Criteria\FindPermissionsResourceCriteria;
use LaccUser\Repositories\PermissionRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      'LACC\Model' => 'LACC\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /**
         * Executa antes de chamar outras hailidades
         * //if retorna true - autorizado
         * //if retorna false - NÃO autorizado
         * //if retorna void - executa a habilidade em questão
         */
        \Gate::before( function ( $user, $ability ) {
            if ( $user->isAdmin() ) {
                return true;
            }
        } );

        
        if ( !app()->runningInConsole() || app()->runningUnitTests() ) {
            /** @var PermissionRepository $permissionRepository */
            $permissionRepository = app( PermissionRepository::class );
            $permissionRepository->pushCriteria( new FindPermissionsResourceCriteria() );
            $permissions = $permissionRepository->all();
            foreach ( $permissions as $p ):
                \Gate::define( "{$p->name}/{$p->resource_name}", function ( $user ) use ( $p ) {
                    return $user->hasRole( $p->roles );
                } );
            endforeach;
        }

    }
}
