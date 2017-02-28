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
use LaccUser\Repositories\UserRepository;
use LaccUser\Services\UserService;
use LaccUser\Annotations\Mapping\Action as ActionAnnotation;
use LaccUser\Annotations\Mapping\Controller as ControllerAnnotation;

/**
 * Class UsersController
 * @package LaccUser\Http\Controllers
 * @ControllerAnnotation(name="users-admin", description="User administration")
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

    private $with = [ 'address' ];

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

    protected $urlDefault = 'laccuser.users.index';

    public function __construct(
      State $state,
      City $city,
      Connection $connection,
      UserService $userService,
      UserRepository $userRepository,
      CityService $cityService,
      AddressRepository $addressRepository,
      AddressService $addressService
    ) {
        $this->state             = $state;
        $this->city              = $city;
        $this->bd                = $connection;
        $this->userService       = $userService;
        $this->userRepository    = $userRepository;
        $this->cityService       = $cityService;
        $this->addressRepository = $addressRepository;
        $this->addressService    = $addressService;

    }

    /**
     * @ActionAnnotation(name="list", description="View user list")
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request )
    {
        $search = $request->get( 'search' );
        $users  = $this->userRepository->paginate( 10 );

        return view( 'laccuser::users.index', compact( 'users', 'search' ) );
    }

    /**
     * @ActionAnnotation(name="view-form-create-user", description="View user creation form")
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $cities      = $this->cityService->getListCitiesInSelect();
        $civilStatus = $this->userService->getPrepareListCivilStatus();
        $typeAddress = $this->userService->getPrepareListTypeAddress();

        return view( 'laccuser::users.create', compact( 'cities', 'civilStatus', 'typeAddress' ) );
    }

    /**
     * @param UserRequest $request
     * @ActionAnnotation(name="store-user", description="Create new user")
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
            if ( $user ) {
                $data[ 'user_id' ] = $user->id;
                $this->addressRepository->create( $data );
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
     * @ActionAnnotation(name="detail-user", description="View user detail")
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
     * @ActionAnnotation(name="view-form-edit-user", description="View user edit form")
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

        return view( 'laccuser::users.edit', compact( 'user', 'states', 'cities', 'civilStatus', 'typeAddress' ) );
    }

    /**
     * @param \LaccUser\Http\Requests\UserRequest $request
     * @param                                     $idUser
     * @ActionAnnotation(name="update-user", description="Updates user data")
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
     * @ActionAnnotation(name="destroy-user", description="Destroy user data")
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
     * @ActionAnnotation(name="advanced-search-user", description="Advanced user search")
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