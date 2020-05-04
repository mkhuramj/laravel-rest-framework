<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; ResourceRegistrar
 * Date: May 04, 2020
 * Time: 01:40:48 PM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['list', 'retrieve', 'create', 'update', 'destroy'];

    protected function addResourceList($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name);
    
        $action = $this->getResourceAction($name, $controller, 'list', $options);
    
        return $this->router->get($uri, $action);
    }

    protected function addResourceRetrieve($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{' . $base . '}';
    
        $action = $this->getResourceAction($name, $controller, 'retrieve', $options);
    
        return $this->router->get($uri, $action);
    }

    protected function addResourceCreate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name);
    
        $action = $this->getResourceAction($name, $controller, 'create', $options);
    
        return $this->router->post($uri, $action);
    }

    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{' . $base . '}';
    
        $action = $this->getResourceAction($name, $controller, 'update', $options);
    
        return $this->router->put($uri, $action);
    }

    protected function addResourceDestro($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name);
    
        $action = $this->getResourceAction($name, $controller, 'destroy', $options);
    
        return $this->router->delete($uri, $action);
    }
}
