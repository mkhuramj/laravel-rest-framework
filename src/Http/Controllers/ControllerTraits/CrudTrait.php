<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; CrudTrait
 * Date: May 04, 2020
 * Time: 12:23:39 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

trait CrudTrait
{
    use ListTrait;
    use RetrieveTrait;
    use CreateTrait;
    use UpdateTrait;
    use DeleteTrait;
}
