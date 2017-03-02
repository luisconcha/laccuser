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
namespace LaccUser\Facade;

use Illuminate\Support\Facades\Facade;
use LaccUser\Annotations\PermissionReader as PermissionsReaderService;

class PermissionReader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PermissionsReaderService::class;
    }
}