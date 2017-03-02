<?php
namespace LaccUser\Http\Controllers\Roles;

use Illuminate\Database\QueryException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use LaccUser\Criteria\FindPermissionsGroupCriteria;
use LaccUser\Criteria\FindPermissionsResourceCriteria;
use LaccUser\Http\Controllers\Controller;
use LaccUser\Http\Requests\PermissionRequest;
use LaccUser\Http\Requests\RoleRequest;
use LaccUser\Repositories\PermissionRepository;
use LaccUser\Repositories\RoleRepository;
use LaccUser\Services\RoleService;
use LaccUser\Annotations\Mapping as Permission;

/**
 * Class RolesController
 * @package LaccUser\Http\Controllers\Roles
 * @Permission\Controller(name="roles-admin", description="Manage user roles")
 */
class RolesController extends Controller
{
    protected $bd;

    private $with = [];

    /**
     * @var \LaccUser\Services\RoleService
     */
    protected $roleService;

    /**
     * @var \LaccUser\Repositories\RoleRepository
     */
    protected $roleRepository;

    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    protected $urlDefault = 'laccuser.role.roles.index';

    public function __construct(
      Connection $connection,
      RoleService $roleService,
      RoleRepository $roleRepository,
      PermissionRepository $permissionRepository
    ) {
        $this->bd                   = $connection;
        $this->roleService          = $roleService;
        $this->roleRepository       = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param Request $request
     * @Permission\Action(name="list-roles", description="View list of user roles")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request )
    {
        $search = $request->get( 'search' );
        $roles  = $this->roleRepository->paginate( 10 );

        return view( 'laccuser::roles.index', compact( 'roles', 'search' ) );
    }

    /**
     * @Permission\Action(name="store-roles", description="Register user roles")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view( 'laccuser::roles.create' );
    }

    /**
     * @param RoleRequest $request
     * @Permission\Action(name="store-roles", description="Register user roles")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store( RoleRequest $request )
    {
        try {
            $this->bd->beginTransaction();
            $data = $request->all();
            $this->roleRepository->create( $data );
            $this->bd->commit();
            $request->session()->flash( 'message',
              [ 'type' => 'success', 'msg' => "Role '{$data['name']}' successfully registered!" ] );

            return redirect()->route( 'laccuser.role.roles.index' );
        } catch ( \Exception $e ) {
            $this->bd->rollBack();
            dd( $e->getMessage() );
        }
    }

    /**
     * @param $id
     * @Permission\Action(name="update-roles", description="Update user roles")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( $id )
    {
        $role = $this->roleService->verifyTheExistenceOfObject( $this->roleRepository, $id, $this->with );

        return view( 'laccuser::roles.edit', compact( 'role' ) );
    }

    /**
     * @param RoleRequest $request
     * @param             $idRole
     * @Permission\Action(name="update-roles", description="Update user roles")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update( RoleRequest $request, $idRole )
    {
        //$data  = $request->all();
        $data  = $request->except( 'permissions' );
        $urlTo = $this->roleService->checksTheCurrentUrl( $data[ 'redirect_to' ], $this->urlDefault );
        try {
            $this->roleService->verifyTheExistenceOfObject( $this->roleRepository, $idRole, $this->with );
            $this->bd->beginTransaction();
            $this->roleRepository->update( $data, $idRole );
            $this->bd->commit();
            $request->session()->flash( 'message',
              [ 'type' => 'success', 'msg' => "Role '{$data['name']}' successfully updated!" ] );

        } catch ( QueryException $e ) {
            $this->bd->rollBack();
            $request->session()->flash( 'error',
              [ 'type' => 'error', 'msg' => "Could not change role($idRole)." ] );
        }

        return redirect()->to( $urlTo );
    }

    /**
     * @param         $id
     * @param Request $request
     * @Permission\Action(name="destroy-roles", description="Delete user roles")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $id, Request $request )
    {
        try {
            $this->roleService->verifyTheExistenceOfObject( $this->roleRepository, $id, $this->with );
            $this->roleRepository->delete( $id );
            $request->session()->flash( 'message', [ 'type' => 'success', 'msg' => 'Role deleted successfully!' ] );
        } catch ( QueryException $ex ) {
            $request->session()->flash( 'error',
              [
                'type' => 'danger',
                'msg'  => 'The user role can not be deleted. It is related to other records.',
              ] );
        }

        return redirect()->route( $this->urlDefault );
    }

    /**
     * @param $id
     * @Permission\Action(name="edit-role-permissions", description="Edit role permissions")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPermissions( $id )
    {
        $role = $this->roleRepository->find( $id );
        //
        $this->permissionRepository->pushCriteria( new FindPermissionsResourceCriteria() );
        $permissions = $this->permissionRepository->all();
        //
        $this->permissionRepository->resetCriteria();
        $this->permissionRepository->pushCriteria( new FindPermissionsGroupCriteria() );
        $permissionsGroup = $this->permissionRepository->all( [ 'name', 'description' ] );

        return view( 'laccuser::roles.permissions', compact( 'role', 'permissions', 'permissionsGroup' ) );
    }

    /**
     * @param PermissionRequest $request
     * @param                   $id
     * @Permission\Action(name="edit-role-permissions", description="Edit role permissions")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermissions( PermissionRequest $request, $id )
    {
        $data = $request->only( 'permissions' );
        $this->roleRepository->update( $data, $id );
        $urlTo = $this->roleService->checksTheCurrentUrl( $request->get( 'redirect_to' ), $this->urlDefault );
        $request->session()->flash( 'message',
          [ 'type' => 'success', 'msg' => "Successfully assigned permissions!" ] );

        return redirect()->to( $urlTo );
    }
}