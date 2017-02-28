<?php
namespace LaccUser\Repositories;

use LACC\Criteria\CriteriaTrashedTrait;
use LACC\Repositories\Traits\BaseRepositoryTrait;
use LACC\Repositories\Traits\RepositoryRestoreTrait;
use LaccUser\Models\Role;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RoleRepositoryEloquent
 * @package namespace LACC\Repositories;
 */
class RoleRepositoryEloquent extends BaseRepository implements RoleRepository
{
    use BaseRepositoryTrait, CriteriaTrashedTrait, RepositoryRestoreTrait;

    protected $fieldSearchable = [
      'id',
      'name'        => 'like',
      'description' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app( RequestCriteria::class ) );
    }
}
