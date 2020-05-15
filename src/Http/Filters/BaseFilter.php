<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; BaseFilter
 * Date: May 05, 2020
 * Time: 01:17:52 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseFilter
{
    /**
     * The querySet given to filter
     */
    protected $querySet;

    /**
     * The field to apply fitler on
     */
    protected $lookUpValue;

    /**
     * The list of the methods, given by the child classes, to use as filter on the given querySet. 
     */
    protected $methodsToFilter;

    public function __construct(Builder $querySet, Request $request)
    {
        $this->querySet = $querySet;
        $this->request = $request;
    }

    /**
     * Loops over the list of methods given to filter the querySet
     */
    public function filter()
    {
        foreach ($this->methodsToFilter AS $lookUpField => $method)
            $this->applyFilter($lookUpField, $method, $this->querySet);
        return $this->querySet;
    }

    /**
     * Checks if the queryparms requestHas the value
     */
    private function requestHas($lookUpField)
    {
        $this->lookUpValue = $this->request->get($lookUpField);
        if (is_null($this->lookUpValue))
            return FALSE;

        if (strpos($this->lookUpValue, '|'))
            $this->lookUpValue = explode('|', $this->lookUpValue);
        return TRUE;
    }

    /**
     * Apply filter for a filter method under the current loop.
     */
    private function applyFilter($lookUpField, $method)
    {
        if ($this->requestHas($lookUpField))
            $this->querySet = $this->$method($this->querySet);
        return $this->querySet;
    }
}
