<?php
use Illuminate\Database\Migrations\Migration;
use LaccUser\Models\Role;
use LaccUser\Models\User;

class CreateAclData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roleAdmin = Role::create( [
          'name'        => config( 'laccuser.acl.role_admin' ),
          'cor'         => '#900',
          'description' => 'Papel do usuário administrador do sistema',
        ] );
        $user      = User::where( 'email', config( 'laccuser.user_default.email' ) )->first();
        $user->roles()->save( $roleAdmin );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roleAdmin = Role::where( 'name', config( 'laccuser.acl.role_admin' ) )->first();
        $user      = User::where( 'email', config( 'laccuser.user_default.email' ) )->first();
        $user->roles()->detach( $roleAdmin->id );
        $roleAdmin->forceDelete();
    }
}
