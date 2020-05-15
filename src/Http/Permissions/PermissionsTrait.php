<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; PermissionsTrait
 * Date: May 05, 2020
 * Time: 01:49:36 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Permissions;

trait PermissionsTrait
{
    protected function actionIsAllowed($request, $user, $permissionName)
    {
        return in_array($permissionName, $user->permissions);
    }

    protected function objectIsAllowedToUpdate($user, $object)
    {
        return $object->created_by === $user->id;
    }
    
    protected function objectIsAllowedToRetrieve($user, $object)
    {
        return $object->created_by === $user->id;
    }

    protected function objectIsAllowedToDelete($user, $object)
    {
        return $object->created_by === $user->id;
    }
}
