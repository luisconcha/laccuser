<?php
/**
 * File: Controller.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 28/02/17
 * Time: 00:20
 * Project: lacc_editora
 * Copyright: 2017
 */
namespace LaccUser\Annotations\Mapping;

/**
 * Class Controller
 * @package LaccUser\Annotations\Mapping
 * @Annotation
 * @Target("CLASS")
 */
class Controller
{
    public $name;
    public $description;
}