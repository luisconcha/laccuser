<?php
/**
 * File: PermissionReader.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 28/02/17
 * Time: 00:36
 * Project: lacc_editora
 * Copyright: 2017
 */
namespace LaccUser\Annotations;

use Doctrine\Common\Annotations\Reader;
use LaccUser\Annotations\Mapping\Action;
use LaccUser\Annotations\Mapping\Controller;

class PermissionReader
{
    /**
     * @var Reader $reader
     */
    private $reader;

    public function __construct( Reader $reader )
    {
        $this->reader = $reader;
    }

    /**
     * Get permission all controllers
     */
    public function getPermissions()
    {
        $controllerClasses = $this->getControllers();
        //dd($controllerClasses);
        $declared    = get_declared_classes();
        $permissions = [];
        foreach ( $declared as $className ):
            $rc = new \ReflectionClass( $className );
            if ( in_array( $rc->getFileName(), $controllerClasses ) ) {
                $permission = $this->getPermission( $className );
                if ( count( $permission ) ) {
                    $permissions = array_merge( $permissions, $permission );
                }
            }
        endforeach;

        return $permissions;
    }

    /**
     * Get permission specific controllers
     *
     * @param $controllerClass
     *
     * @return array
     */
    public function getPermission( $controllerClass, $action = null )
    {
        $rc = new \ReflectionClass( $controllerClass );
        /** @var Controller $controllerAnnotation */
        $controllerAnnotation = $this->reader->getClassAnnotation( $rc, Controller::class );
        $permissions          = [];
        if ( $controllerAnnotation ) {
            $permission = [
              'name'        => $controllerAnnotation->name,
              'description' => $controllerAnnotation->description,
            ];
            $rcMethods  = !$action ? $rc->getMethods() : [ $rc->getMethod( $action ) ];
            foreach ( $rcMethods as $rcMethod ):
                /** @var Action $actionAnnotation */
                $actionAnnotation = $this->reader->getMethodAnnotation( $rcMethod, Action::class );
                if ( $actionAnnotation ) {
                    $permission[ 'resource_name' ]        = $actionAnnotation->name;
                    $permission[ 'resource_description' ] = $actionAnnotation->description;
                    $permissions[]                        = ( new \ArrayIterator( $permission ) )->getArrayCopy();
                }
            endforeach;
        }

        return $permissions;
    }

    /**
     * @return array
     */
    private function getControllers()
    {
        $dirs   = [
          config( 'laccuser.acl.controllers_annotations' ),
          config( 'laccbook.acl.controllers_annotations' ),
        ];
        $config = include __DIR__ . '/../Config/config.local.php';
        $dirs   = array_merge( $dirs, $config[ 'acl' ][ 'controllers_annotations' ] );
        $files  = [];
        foreach ( $dirs as $dir ):
            if ( count( $dir ) > 0 ) {
                foreach ( \File::allFiles( $dir ) as $file ):
                    $files[] = $file->getRealPath();
                    require_once $file->getRealPath();
                endforeach;
            }
        endforeach;

        return $files;
    }

}