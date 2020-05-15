<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; RetrieveTrait
 * Date: May 04, 2020
 * Time: 11:31:01 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

use Illuminate\Http\JsonResponse;

use LaravelRestFramework\Services\HttpStatus;
use LaravelRestFramework\Http\Controllers\Api;

trait RetrieveTrait
{
    public function retrieve(string $id, array $args=[], array $kwargs=[]): JsonResponse
    {
        $instance = $this->getObject();
        return Api::response([new $this->modelResource($instance)], HttpStatus::CREATED);
    }
}
