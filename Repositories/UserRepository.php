<?php
namespace LaccUser\Repositories;

use LACC\Criteria\CriteriaTrashedInterface;
use Prettus\Repository\Contracts\RepositoryCriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository
 * @package namespace LACC\Repositories;
 */
interface UserRepository extends
	RepositoryInterface,
	RepositoryCriteriaInterface,
	CriteriaTrashedInterface
{
		//
}
