<?php
namespace LaccUser\Repositories;

use LACC\Criteria\CriteriaTrashedInterface;
use Prettus\Repository\Contracts\RepositoryCriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RoleRepository
 * @package namespace LACC\Repositories;
 */
interface RoleRepository extends
	RepositoryInterface,
	RepositoryCriteriaInterface,
	CriteriaTrashedInterface
{
		//
}
