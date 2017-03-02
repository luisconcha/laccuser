<?php
namespace LaccUser\Http\Controllers;

use LaccUser\Http\Requests\UserPasswordRequest;
use LaccUser\Repositories\UserRepository;
use LaccUser\Services\UserService;

/**
 * Class UsersPasswordController
 * @package LaccUser\Http\Controllers
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
     */
    public function edit()
    {
        $user = \Auth::user();

        return view( 'laccuser::users.user-settings.password', compact( 'user' ) );
    }

    /**
     * @param UserPasswordRequest $userPasswordRequest
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