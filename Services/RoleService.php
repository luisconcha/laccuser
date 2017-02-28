<?php
/**
 * File: RoleService.php
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
use LaccUser\Models\Role;

class RoleService extends BaseService
{
    /**
     * @var Role
     */
    protected $roleModel;

    public function __construct( Role $role )
    {
        $this->roleModel = $role;
    }

}