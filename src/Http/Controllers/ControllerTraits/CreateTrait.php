<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; CreateTrait
 * Date: May 04, 2020
 * Time: 10:57:38 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

use LaravelRestFramework\Services\HttpStatus;
use LaravelRestFramework\Http\Controllers\Api;
use Symfony\Component\Debug\Exception\FatalThrowableError;

trait CreateTrait
{
    public function create(array $args=[], array $kwargs=[]): JsonResponse
    {
        try {
            DB::beginTransaction();
            $apiRequest = $this->getFormRequest();
            $apiRequest->validate();
            // try {
            //     dd($apiRequest);
            //     $apiRequest->validate();
            // } catch (Exception $err) {
            //     dd($apiRequest);
            // }
            $instance = $this->performCreate($apiRequest);
            $instanceData = new $this->modelResource($instance);
            DB::commit();
            return Api::response([$instanceData], HttpStatus::CREATED);
        } catch (FatalThrowableError $th) {
            DB::rollback();
            Api::problem($th->getMessage(), "Server Error", HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    public function performCreate($apiRequest)
    {
        return $apiRequest->createInstance($this);
    }
}