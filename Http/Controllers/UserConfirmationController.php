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

use LaccUser\Annotations\Mapping\Action as ActionAnnotation;
use LaccUser\Annotations\Mapping\Controller as ControllerAnnotation;

/**
 * Class UserConfirmationController
 * @package LaccUser\Http\Controllers
 * @ControllerAnnotation(name="users-confirmation-email", description="User confirmation - email verification")
 */
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

    /**
     * @ActionAnnotation(name="redirect-after-verification", description="Verified action for redirect")
     * @return string
     */
    public function redirectAfterVerification()
    {
        $this->loginUser();
        return route('laccuser.user_password.edit');
    }

    /**
     * @ActionAnnotation(name="login-user", description="Performs the user login after verifying the token sent by email")
     */
    private function loginUser()
    {
        $email = \Request::get('email');
        $user = $this->userRepository->findByField('email', $email)->first();
        \Auth::login($user);
    }
}