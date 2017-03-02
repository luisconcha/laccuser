<?php
namespace LaccUser\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use LACC\Criteria\CriteriaTrashedTrait;
use LACC\Repositories\Traits\BaseRepositoryTrait;
use LACC\Repositories\Traits\RepositoryRestoreTrait;
use LaccUser\Models\Role;

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
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update( array $attributes, $id )
    {
        $model = parent::update( $attributes, $id );
        if ( isset( $attributes[ 'permissions' ] ) ) {
            $model->permissions()->sync( $attributes[ 'permissions' ] );
        }

        return $model;
    }

    /**
     * @param      $column
     * @param null $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function listsWithMutators( $column, $key = null )
    {
        /** @var  Collection $collection */
        $collection = $this->all();

        return $collection->pluck( $column, $key );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria( app( RequestCriteria::class ) );
    }
}
