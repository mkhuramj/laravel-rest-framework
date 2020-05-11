<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; BasePermission
 * Date: May 09, 2020
 * Time: 02:01:04 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Permissions;

use LaravelRestFramework\Http\Permissions\BasePermission;

class AllowAnyPermission extends BasePermission
{
    public function _hasPermission($request, $controller)
    {
        return isset($request->user);
    }

    public function _hasObjectPermission($request, $controller, $object)
    {
        return isset($request->user);
    }
}
