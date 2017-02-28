<?php
namespace LaccUser\Http\Controllers\Roles;

use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use LaccUser\Http\Controllers\Controller;
use LaccUser\Http\Requests\RoleRequest;
use LaccUser\Repositories\RoleRepository;
use LaccUser\Services\RoleService;

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

    protected $urlDefault = 'laccuser.role.roles.index';

    public function __construct(
      Connection $connection,
      RoleService $roleService,
      RoleRepository $roleRepository
    ) {
        $this->bd             = $connection;
        $this->roleService    = $roleService;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param Request $request
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view( 'laccuser::roles.create' );
    }

    /**
     * @param RoleRequest $request
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
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update( RoleRequest $request, $idRole )
    {
        try {
            $this->roleService->verifyTheExistenceOfObject( $this->roleRepository, $idRole, $this->with );
            $this->bd->beginTransaction();
            $data = $request->all();
            $this->roleRepository->update( $data, $idRole );
            $this->bd->commit();
            $urlTo = $this->roleService->checksTheCurrentUrl( $data[ 'redirect_to' ], $this->urlDefault );
            $request->session()->flash( 'message',
              [ 'type' => 'success', 'msg' => "Role '{$data['name']}' successfully updated!" ] );

            return redirect()->to( $urlTo );

        } catch ( \Exception $e ) {
            $this->bd->rollBack();
            dd( $e->getMessage() );
        }
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $id, Request $request )
    {
        $this->roleService->verifyTheExistenceOfObject( $this->roleRepository, $id, $this->with );
        $this->roleRepository->delete( $id );
        $request->session()->flash( 'message', [ 'type' => 'success', 'msg' => 'Role deleted successfully!' ] );

        return redirect()->route( 'laccuser.role.roles.index' );
    }
}