<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; ListTrait
 * Date: May 04, 2020
 * Time: 11:26:53 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers\ControllerTraits;

use Illuminate\Http\JsonResponse;

use LaravelRestFramework\Services\HttpStatus;
use LaravelRestFramework\Http\Controllers\Api;

trait ListTrait
{
    public function list(array $args=[], array $kwargs=[]): JsonResponse
    {
        $querySet = $this->filterQuerySet($this->getQuerySet());

        // $page = $this->request('page');
        // if ($page !== null) {
        //     $collection = $querySet->paginate();
        // } else {
        //     $collection = $querySet->paginate();
        // }
        // dd($querySet);
        $collection = $querySet->paginate();

        // dd($this->modelResource);
        // return response()->json(
        //     ['success' => TRUE, new $this->modelResource($collection)]
        // );
        return Api::collectionResponse([
                new $this->modelResource($collection)
            ],
            HttpStatus::CREATED
        );
    }
}
