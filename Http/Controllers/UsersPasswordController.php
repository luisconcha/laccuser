<?php
namespace LaccUser\Http\Controllers;

use LaccUser\Http\Requests\UserPasswordRequest;
use LaccUser\Repositories\UserRepository;
use LaccUser\Services\UserService;
use LaccUser\Annotations\Mapping\Action as ActionAnnotation;
use LaccUser\Annotations\Mapping\Controller as ControllerAnnotation;

/**
 * Class UsersPasswordController
 * @package LaccUser\Http\Controllers
 * @ControllerAnnotation(name="users-password-admin", description="User administration")
 */
class UsersPasswordController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UsersPasswordController constructor.
     *
     * @param UserRepository $userRepository
     * @param UserService    $userService
     */
    public function __construct( UserRepository $userRepository, UserService $userService )
    {
        $this->userRepository = $userRepository;
        $this->userService    = $userService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @ActionAnnotation(name="view-form-user-settings-password", description="View password change form")
     */
    public function edit()
    {
        $user = \Auth::user();

        return view( 'laccuser::users.user-settings.password', compact( 'user' ) );
    }

    /**
     * @param UserPasswordRequest $userPasswordRequest
     * @ActionAnnotation(name="update-settings-password", description="Change password")
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update( UserPasswordRequest $userPasswordRequest )
    {
        $user               = \Auth::user();
        $data               = $userPasswordRequest->all();
        $data[ 'password' ] = $this->userService->setEncryptPassword( $data[ 'password' ] );
        $this->userRepository->update( $data, $user->id );
        $userPasswordRequest->session()->flash( 'message',
          [ 'type' => 'success', 'msg' => 'Password changed successfully!' ] );

        return redirect()->route( 'laccuser.user_password.edit' );
    }

}