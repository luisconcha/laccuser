<?php
/**
 * File: Action.php
 * Created by: Luis Alberto Concha Curay.
 * Email: luisconchacuray@gmail.com
 * Language: PHP
 * Date: 28/02/17
 * Time: 00:46
 * Project: lacc_editora
 * Copyright: 2017
 */
namespace LaccUser\Annotations\Mapping;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Action
 * @package   LaccUser\Annotations\Mapping
 * @Annotation
 * @Target("METHOD")
 */
class Action
{
    public $name;
    public $description;
}