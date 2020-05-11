<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; BasePermission
 * Date: May 05, 2020
 * Time: 01:31:04 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Permissions;


abstract class BasePermission
{
    use PermissionsTrait;

    public $message = 'Permission denied';

    public function hasPermission($request, $controller)
    {
        return $this->_hasPermission($request, $controller);
    }

    public function hasObjectPermission($request, $controller, $object)
    {
        return $this->_hasObjectPermission($request, $controller, $object);
    }
}
