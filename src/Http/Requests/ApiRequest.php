<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; ApiRequest
 * Date: May 05, 2020
 * Time: 02:31:18 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

 namespace LaravelRestFramework\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\Model;

use LaravelRestFramework\Http\Controllers\LaravelRestFrameworkController;
use LaravelRestFramework\Services\HttpStatus;

class ApiRequest extends FormRequest
{
    /**
     * Failed validation disable redirect
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                $validator->errors(),
                HttpStatus::UNPROCESSABLE_ENTITY
            )
        );
    }

    /**
     * Creates the required instance
     *
     * @param LaravelRestFrameworkController $ccontroller
     */
    public function createInstance($controller): Model
    {
        if ($this->rules())
            return $controller->modelClass::create($this->validated());
        else
            return $controller->modelClass::create($this->validationData());
    }
    
    /**
     * Update the required instance
     *
     * @param LaravelRestFrameworkController $ccontroller
     * @param Illuminate\Database\Eloquent\Model $instance
     */    
    public function updateInstance($controller, $instance): Model
    {
        if ($this->rules())
            $instance->fill($this->validated())->update();
        else
            $instance->fill($this->validationData())->update();
        return $instance;
    }
}
