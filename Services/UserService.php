<?php
/**
 * File: UserService.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 04/02/17
 * Time: 17:24
 * Project: lacc_editora
 * Copyright: 2017
 */

namespace LaccUser\Services;


use LACC\Services\BaseService;
use LaccUser\Models\User;

class UserService extends BaseService
{
    /**
     * @var User
     */
    protected $usermodel;

    public function __construct(User $user)
    {
        $this->usermodel = $user;
    }

    public function getPrepareListCivilStatus()
    {
        $arrStatus = [
            ''  => '--Select a civil status--',
            '1' => User::CASADO,
            '2' => User::VIUVO,
            '3' => User::DIVORCIADO,
            '4' => User::SOLTEIRO,
            '5' => User::UNKNOWN,
        ];
        return $arrStatus;
    }

    public function getTypeCivilStatus($id)
    {
        $civilSatus = '';
        switch ($id):
            case '1':
                $civilSatus = User::CASADO;
                break;
            case '2':
                $civilSatus = User::VIUVO;
                break;
            case '3':
                $civilSatus = User::DIVORCIADO;
                break;
            case '4':
                $civilSatus = User::SOLTEIRO;
                break;
            case '5':
                $civilSatus = User::UNKNOWN;
                break;
        endswitch;

        return $civilSatus;
    }

    public function getPrepareListTypeAddress()
    {
        $arrTypeAddres = [
            ''  => '--Select an address type --',
            '1' => User::CASA,
            '2' => User::APARTAMENTO,
            '3' => User::SOBRADO,
            '4' => User::CHACARA,
            '5' => User::LOFT,

        ];

        return $arrTypeAddres;
    }

    public function setEncryptPassword($password)
    {
        return bcrypt(trim($password));
    }

    public function generateRemenberToken()
    {
        return str_random(10);
    }
}