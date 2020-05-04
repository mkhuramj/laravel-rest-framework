<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LaravelRestFrameworkController
 * Date: May 04, 2020
 * Time: 08:35:17 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

use LaravelRestFramework\Http\Api;
use LaravelRestFramework\Services\HttpStatus;
use Illuminate\Routing\Controller;
use ReflectionException;

class LaravelRestFrameworkController extends Controller
{

    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The request, of the "Illuminate\Http\Request", created and injected by Laravel.
     */
    public $request;

    /**
     * The name of the model, in lower, binded to the controller
     */
    public $model;

    /**
     * The fully qualified model class
     */
    public $modelClass;

    /**
     * FormRequests will get registered in the controller to get used by the CRUD Traits.
     */
    public $apiRequests;

    /**
     * The current action, out of ["create", "update", "list", "retrieve", "delete"], the user want to perfrom
     */
    public $action;

    /**
     * Object of type Illuminate\Database\Eloquent\Builder to hold the querySet (QueryBuilder)
     */
    public $querySet;

    /**
     * The field to lookup while getting object using getObject.
     */
    public $lookupField = 'id';

    /**
     * The id of the instance currently under the process during the request.
     */
    public $instanceId;

    /**
     * Holds the permissions to check the validaty of the action requested in the current request.
     */
    public $permissions = [];

    /**
     * Holds the permissions to check the validaty of the action requested in the current request.
     */
    public $filterClass;

    /**
     * The resource based on the action , out of ["create", "update", "list", "retrieve", "delete"],
     * the user want to perfrom. Collection Resource for the "list" action.
     */
    public $modelResource;

    public function __construct(Request $request)
    {
        $this->app = app();
        if ($this->app->runningInConsole())
            return TRUE;
        $this->request = $request;
        $this->action = $request->route()->getActionMethod();
        $this->modelResource = $this->getResourceClass();
        $this->modelClass = $this->getModelClass();
        $this->instanceId = $this->request->route($this->model);
        $this->permissions = $this->getPermissions();
        $this->checkPermissions($this->request);
    }

    /**
     * Given a queryset, filter it with whichever filter backend is in use.
     * You are unlikely to want to override this method, although you may need
     * to call it either from a list view, or from a custom `get_object`
     * method if you want to apply the configured filtering backend to the
     * default querySet
     */
    public function filterQuerySet($querySet)
    {
        if (!$this->filterClass)
            return $querySet;

        $this->filterClass = new $this->filterClass($querySet, $this->request);
        $querySet = $this->filterClass->filter($querySet);
        return $querySet;
    }

    public function getQuerySet()
    {
        $querySet = $this->querySet;
        if (!$querySet) {
            throw new HttpResponseException(
                response()->json([
                    'error' => __CLASS__ . " controller should either include a `querySet` attribute, or override the `getQueryset()` method."
                ], HttpStatus::UNPROCESSABLE_ENTITY)
            );
        }
        if ($querySet instanceof Builder)
            $querySet = $querySet->all();
    }

    /**
     * Check if the request should be permitted.
     * Raises an appropriate exception if the request is not permitted.
     */
    public function checkPermissions($request)
    {
        foreach ($this->permissions as $permission) {
            if (!$permission->hasPermission($request, $this))
                $this->permissionDenied($this->request, $this, $permission->message);
        }
    }

    /**
     * Check if the request should be permitted for a given object.
     *  Raises an appropriate exception if the request is not permitted.
     */
    public function checkObjectPermissions($object)
    {
        foreach ($this->permissions as $permission) {
            if (!$permission->hasObjectPermission($this->request, $this, $object))
                $this->permissionDenied();
        }
    }

    public function permissionDenied($request, $controller, $message)
    {
        throw new HttpResponseException(
            Api::problem($message, 'Forbiddn', HttpStatus::FORBIDDEN)
        );
    }

    public function getFormRequest()
    {
        try {
            return (app()->make($this->apiRequests[$this->action]));
        } catch (ReflectionException $exc) {
            return $this->request;
        }
    }

    /**
     * Returns the object the view is displaying. You may want to override this if you need to provide non-standard
     * queryset lookups.  Eg if objects are referenced using multiple keyword arguments in the url conf.
     */
    public function getObject()
    {
        $querySet = $this->filterQuerySet($this->getQuerySet());

        $object = $this->getObjectOr404($querySet);
        $this->checkObjectPermissions($object);
        return $object;
    }

    public function getObjectOr404($querySet)
    {
        try {
            return $querySet->where($this->lookupField, $this->instanceId)->firstOrFail();
        } catch (ModelNotFoundException $exc) {
            throw new HttpResponseException(
                Api::problem($exc->getMessage(), 'Not Found', HttpStatus::NOT_FOUND)
            );
        }
    }

    public function getPermissions()
    {
        $permissions = [];
        foreach ($this->permissions as $permission) {
            array_push($permissions, new $permission());
        }
        return $permissions;
    }

    public function getResourceClass()
    {
        $resource = 'Resource';
        if ($this->action === 'list')
            $resource = 'Collection';
        return $this->getRootNameSpace() . "Http\\Resources\\" . ucfirst($this->model) . $resource;
    }

    public function getModelClass()
    {
        return  $this->getRootNameSpace() . ucfirst($this->model);
    }

    public function getRootNameSpace()
    {
        return $this->app->getNamespace();
    }
}
