<?php
/**
 * File: BaseService.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 04/02/17
 * Time: 17:11
 * Project: lacc_editora
 * Copyright: 2017
 */

namespace LaccUser\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseService
{
     /**
     * @param $currentUrl
     * @param $defaultRoute
     * @return string
     */
    public function checksTheCurrentUrl( $currentUrl, $defaultRoute )
    {
        $urlTo = ( $currentUrl ) ? $currentUrl : route( $defaultRoute );

        return $urlTo;
    }

    public function verifyTheExistenceOfObject( $repository, $id, $with = null )
    {
        if( !( $object =  $repository->find( $id ) ) ){
            throw new modelnotfoundexception( 'Object not found' );
        }
        return $object = $repository->with( $with )->find( $id );
    }

}