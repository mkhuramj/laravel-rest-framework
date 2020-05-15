<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LaravelRestFrameworkController
 * Date: May 04, 2020
 * Time: 08:35:17 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use LaravelRestFramework\Http\Controllers\Api;
use LaravelRestFramework\Services\HttpStatus;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\User as Authenticatable
use ReflectionException;

class LaravelRestFrameworkController extends Controller
{

    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The request
     *
     * @var Illuminate\Http\Request
     */
    public $request;

    /**
     * The name of the model in lower case
     *
     * @var string
     */
    public $model;

    /**
     * The fully qualified model class
     *
     * @var \Illumiate\Database\Eloquent\Model
     */
    public $modelClass;

    /**
     * ApiRequests will get registered in the controller and will used by the CRUD Traits.
     * 
     * @var array [LaravelRestFramework\Http\Requests\ApiRequest]
     */
    public $apiRequests;

    /**
     * The current action, out of ["create", "update", "list", "retrieve", "delete"], the user want to perfrom
     * 
     * @var string
     */
    public $action;

    /**
     * The querySet for the model binded with the Countroller
     *
     * @var Illuminate\Database\Eloquent\Builder
     */
    public $querySet;

    /**
     * The field to lookup while getting object using getObject.
     *
     * @var str
     */
    public $lookupField = 'id';

    /**
     * The id of the instance currently under the process during the request.
     *
     * @var str|int
     */
    public $instanceId;

    /**
     * Holds the permissions to check the validaty of the action requested in the current request.
     *
     * @var array [LaravelRestFramework\Http\Permissions\BasePermission]
     */
    public $permissions = [];

    /**
     * Holds the permissions to check the validaty of the action requested in the current request.
     *
     * @var LaravelRestFramework\Http\Filters\BaseFilter
     */
    public $filterClass;

    /**
     * The resource based on the action , out of ["create", "update", "list", "retrieve", "delete"],
     * the user want to perfrom. Collection Resource for the "list" action.
     * 
     * @var array
     */
    public $modelResource;

    public function __construct(Request $request)
    {
        $this->app = app();
        if ($this->app->runningInConsole())
            return TRUE;

        $this->request = $request;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $this->init($this->request);
        return parent::callAction($method, $parameters);
    }

    /**
     * Sets the necessary things to be used for the permissions and filters.
     *
     * @param Illuminate\Http\Request $request
     */
    public function init(Request $request): void
    {
        $user = $this->getLoggedInUser();
        try {
            $request->user = User::with('permissions')
                                 ->find($user->id);
        } catch (RelationNotFoundException $exc) {
            $request->user = User::find($user->id);
            $request->user->permissions = [];
        }
        
        $this->request = $request;
        $this->action = $request->route()->getActionMethod();
        $this->modelResource = $this->getResourceClass();
        $this->modelClass = $this->getModelClass();
        $this->instanceId = $this->request->route($this->model);
        $this->permissions = $this->getPermissions();

        $this->checkPermissions($this->request);
    }

    /**
     * Given a queryset, filter it with whichever filter backend is in use. You are unlikely to want to
     * override this method, although you may need to call it either from a list view, or from a custom
     * `get_object` method if you want to apply the configured filtering backend to the default querySet
     * 
     * @param Illuminate\Database\Eloquent\Builder $querySet
     */
    public function filterQuerySet(Builder $querySet): Builder
    {
        if (!$this->filterClass)
            return $querySet;

        $this->filterClass = new $this->filterClass($querySet, $this->request);
        $querySet = $this->filterClass->filter($querySet);
        return $querySet;
    }

    /**
     * querySet provider
     */
    public function getQuerySet(): void
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

    /**
     * get the requested object or throw ModelNotFoundException exception
     */
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
        return $this->getRootNameSpace() . ucfirst($this->model) . $resource;
    }

    /**
     * Get qualified Model Calss
     */
    public function getModelClass(): string
    {
        return  $this->getRootNameSpace() . ucfirst($this->model);
    }

    /**
     * get the root name space
     */
    public function getRootNameSpace(): string
    {
        return $this->app->getNamespace();
    }

    /**
     * get the currently authenticated/loggedin user
     */
    public function getLoggedInUser(): Authenticable
    {
        return auth()->user();
    }
}
