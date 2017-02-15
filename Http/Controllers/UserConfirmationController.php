<?php
/**
 * File: UserConfirmationController.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 13/02/17
 * Time: 23:45
 * Project: lacc_editora
 * Copyright: 2017
 */

namespace LaccUser\Http\Controllers;

use Jrean\UserVerification\Traits\VerifiesUsers;
use LaccUser\Repositories\UserRepository;

class UserConfirmationController extends Controller
{
    use VerifiesUsers;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function redirectAfterVerification()
    {
        $this->loginUser();
        return route('laccuser.user_password.edit');
    }


    private function loginUser()
    {
        $email = \Request::get('email');
        $user = $this->userRepository->findByField('email', $email)->first();
        \Auth::login($user);
    }
}