<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; DeleteTrait
 * Date: May 04, 2020
 * Time: 11:12:25 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use LaravelRestFramework\Services\HttpStatus;
use LaravelRestFramework\Http\Controllers\Api;

trait DeleteTrait
{
    public function destroy(string $id, array $args=[], array $kwargs=[]): JsonResponse
    {
        $instance = $this->getObject();
        $this->performDestroy($instance);

        return Api::response([], HttpStatus::NO_CONTENT);
    }

    public function performDestroy($instance)
    {
        return $instance->delete();
    }
}
