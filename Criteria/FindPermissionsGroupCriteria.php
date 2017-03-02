<?php
namespace LaccUser\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FindPermissionsGroupCriteria
 * @package namespace LACC\Criteria;
 */
class FindPermissionsGroupCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply( $model, RepositoryInterface $repository )
    {
        return $model->groupBy( 'name', 'description' );
    }
}
