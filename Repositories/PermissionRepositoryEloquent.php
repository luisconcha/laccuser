<?php
/**
 * File: PermissionRepositoryEloquent.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 28/02/17
 * Time: 12:21
 * Project: lacc_editora
 * Copyright: 2017
 */
namespace LaccUser\Repositories;

use LaccUser\Models\Permission;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PermissionRepositoryEloquent
 * @package LaccUser\Repositories
 */
class PermissionRepositoryEloquent extends BaseRepository implements PermissionRepository
{
    /**
     * @return mixed
     */
    public function model()
    {
        return Permission::class;
    }

    public function boot()
    {
        $this->pushCriteria( app( RequestCriteria::class ) );
    }

}