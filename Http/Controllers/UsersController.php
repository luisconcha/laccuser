<?php
namespace LaccUser\Http\Controllers;

use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use LACC\Criteria\FormAvancedSearch;
use LACC\Models\City;
use LACC\Models\State;
use LACC\Repositories\AddressRepository;
use LACC\Services\AddressService;
use LACC\Services\CityService;
use LaccUser\Http\Requests\UserDeleteRequest;
use LaccUser\Http\Requests\UserRequest;
use LaccUser\Repositories\RoleRepository;
use LaccUser\Repositories\UserRepository;
use LaccUser\Services\UserService;
use LaccUser\Annotations\Mapping as Permission;

/**
 * Class UsersController
 * @package LaccUser\Http\Controllers
 * @Permission\Controller(name="users-admin", description="User administration")
 */
class UsersController extends Controller
{
    /**
     * @var \LACC\Models\State
     */
    protected $state;
    /**
     * @var City
     */
    protected $city;

    protected $bd;

    private $with = [ 'address', 'roles' ];

    /**
     * @var \LaccUser\Services\UserService
     */
    protected $userService;

    /**
     * @var CityService
     */
    protected $cityService;

    /**
     * @var AddressService
     */
    protected $addressService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    protected $urlDefault = 'laccuser.users.index';

    public function __construct(
      State $state,
      City $city,
      Connection $connection,
      UserService $userService,
      UserRepository $userRepository,
      CityService $cityService,
      AddressRepository $addressRepository,
      AddressService $addressService,
      RoleRepository $roleRepository
    ) {
        $this->state             = $state;
        $this->city              = $city;
        $this->bd                = $connection;
        $this->userService       = $userService;
        $this->userRepository    = $userRepository;
        $this->cityService       = $cityService;
        $this->addressRepository = $addressRepository;
        $this->addressService    = $addressService;
        $this->roleRepository    = $roleRepository;
    }

    /**
     * @param Request $request
     * @Permission\Action(name="list", description="View user list")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request )
    {
        $search = $request->get( 'search' );
        $users  = $this->prepareListRoles( $this->userRepository->with( $this->with )->paginate( 10 ) );

        return view( 'laccuser::users.index', compact( 'users', 'search' ) );
    }

    /**
     * Prepara campo de role para adicionar cor de fundo quando existe role na listagem de usuários
     *
     * @param $users
     *
     * @return mixed
     */
    private function prepareListRoles( $users )
    {
        foreach ( $users as $user ) {
            if ( $user->roles ) {
                foreach ( $user->roles as $role ) {
                    $role->name = "<span class='label' style='background-color: $role->cor'>" . $role->name . "</span>";
                }
            }
        }

        return $users;
    }

    /**
     * @Permission\Action(name="store-users", description="Store users")
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $cities      = $this->cityService->getListCitiesInSelect();
        $civilStatus = $this->userService->getPrepareListCivilStatus();
        $typeAddress = $this->userService->getPrepareListTypeAddress();
        $roles       = $this->roleRepository->lists( 'name', 'id' );

        return view( 'laccuser::users.create', compact( 'cities', 'civilStatus', 'typeAddress', 'roles' ) );
    }

    /**
     * @param UserRequest $request
     * @Permission\Action(name="store-users", description="Store users")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store( UserRequest $request )
    {
        try {
            $this->bd->beginTransaction();
            $data               = $request->all();
            $data[ 'password' ] = $this->userService->setEncryptPassword( '123456' );
            $user               = $this->userRepository->create( $data );
            //Gera token e envia email
            \UserVerification::generate( $user );
            $subject = config( 'laccuser.email.user_created.subject' );//@seed /path/Modules/LaccUser/Config/config.php
            \UserVerification::emailView( 'laccuser::emails.user-created' );
            \UserVerification::send( $user, $subject );
            //
            if ( $user ) {
                $data[ 'user_id' ] = $user->id;
                $this->addressRepository->create( $data );
                /**
                 * Verifica se existe a role atrelada ao usuario para salvar na tbl pivot role_user
                 */
                if ( !empty( $data[ 'roles' ][ 0 ] ) ) {
                    $user->roles()->sync( $data[ 'roles' ] );
                }

            }
            $this->bd->commit();
            $request->session()->flash( 'message',
              [ 'type' => 'success', 'msg' => "User '{$data['name']}' successfully registered!" ] );

            return redirect()->route( 'laccuser.users.index' );
        } catch ( \Exception $e ) {
            $this->bd->rollBack();
            dd( $e->getMessage() );
        }
    }

    /**
     * @param $id
     * @Permission\Action(name="view-user-detail", description="View user detail")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail( $id )
    {
        $user                   = $this->userService->verifyTheExistenceOfObject( $this->userRepository, $id,
          $this->with );
        $user[ 'civil_status' ] = $this->userService->getTypeCivilStatus( $user[ 'civil_status' ] );

        return view( 'laccuser::users.detail', compact( 'user' ) );
    }

    /**
     * @param $id
     * @Permission\Action(name="update-users", description="Update users")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( $id )
    {
        $user        = $this->userService->verifyTheExistenceOfObject( $this->userRepository, $id, $this->with );
        $addressUser = $this->addressRepository->findByField( 'user_id', $user->id )->toArray();
        if ( $addressUser ) {
            $user[ 'city_id' ]      = $addressUser[ 0 ][ 'city_id' ];
            $user[ 'address' ]      = $addressUser[ 0 ][ 'address' ];
            $user[ 'district' ]     = $addressUser[ 0 ][ 'district' ];
            $user[ 'cep' ]          = $addressUser[ 0 ][ 'cep' ];
            $user[ 'type_address' ] = $addressUser[ 0 ][ 'type_address' ];
        }
        $cities      = $this->cityService->getListCitiesInSelect();
        $civilStatus = $this->userService->getPrepareListCivilStatus();
        $typeAddress = $this->userService->getPrepareListTypeAddress();
        $roles       = $this->roleRepository->lists( 'name', 'id' );
        //        $roles = $this->roleRepository->listsWithMutators( 'name', 'id' );
        //        $roles = $user->formRoleAttributte();
        //        $roles = $this->roleRepository->all()->pluck( 'name', 'id' );
        return view( 'laccuser::users.edit', compact( 'user', 'states', 'cities', 'civilStatus', 'typeAddress', 'roles'
        ) );
    }

    /**
     * @param \LaccUser\Http\Requests\UserRequest $request
     * @param                                     $idUser
     * @Permission\Action(name="update-users", description="Update users")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update( UserRequest $request, $idUser )
    {
        try {
            $user = $this->userService->verifyTheExistenceOfObject( $this->userRepository, $idUser, $this->with );
            $this->bd->beginTransaction();
            $data = $request->all();
            if ( $this->userRepository->update( $data, $idUser ) ) {
                $this->addressRepository->update( $data, $user->address->id );
                /**
                 * Verifica se existe a role atrelada ao usuario para salvar na tbl pivot role_user
                 */
                if ( isset( $data[ 'roles' ] ) && empty( !$data[ 'roles' ][ 0 ] ) ) {
                    $user->roles()->sync( $data[ 'roles' ] );
                }
            }
            $this->bd->commit();
            $urlTo = $this->userService->checksTheCurrentUrl( $data[ 'redirect_to' ], $this->urlDefault );
            $request->session()->flash( 'message',
              [ 'type' => 'success', 'msg' => "User '{$data['name']}' successfully updated!" ] );

            return redirect()->to( $urlTo );

        } catch ( \Exception $e ) {
            $this->bd->rollBack();
            dd( $e->getMessage() );
        }
    }

    /**
     * @param                   $id
     * @param Request           $request
     * @param UserDeleteRequest $userDeleteRequest
     * @Permission\Action(name="destroy-user", description="Destroy user data")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( $id, Request $request, UserDeleteRequest $userDeleteRequest )
    {
        $this->userService->verifyTheExistenceOfObject( $this->userRepository, $id, $this->with );
        $this->userRepository->delete( $id );
        $request->session()->flash( 'message', [ 'type' => 'success', 'msg' => 'User deleted successfully!' ] );

        return redirect()->route( 'laccuser.users.index' );
    }

    /**
     * @param Request $request
     * @Permission\Action(name="advanced-search-user", description="Advanced user search")
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function advancedSearch( Request $request )
    {
        $arrSearch = $request->all();
        $this->userRepository->pushCriteria( new FormAvancedSearch( $arrSearch ) );
        $users = $this->userRepository->paginate( 10 );

        return view( 'laccuser::users.advanced-search', compact( 'users', 'arrSearch' ) );
    }
}