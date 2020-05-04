<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; ListTrait
 * Date: May 04, 2020
 * Time: 11:46:15 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

use Illuminate\Http\JsonResponse;

use LaravelRestFramework\Services\HttpStatus;
use LaravelRestFramework\Http\Controllers\Api;

trait UpdateTrait
{
    public function update(string $id, array $args=[], array $kwargs=[]): JsonResponse
    {
        $instance = $this->getObject();
        $formRequest = $this->getFormRequest();
        $formRequest->validate();
        $instance = $this->performUpdate($formRequest, $instance);

        return Api::response([new $this->modelResource($instance)], HttpStatus::CREATED);
    }

    public function performUpdate($formRequest, $instance)
    {
        return $formRequest->updateInstance($this, $instance);
    }
}
