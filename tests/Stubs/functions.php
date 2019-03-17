<?php

if (!function_exists('create')) {
    /**
     * Clear the create factory
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return  mixed
     */
    function create(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->create($attribute);
    }
}

if (!function_exists('make')) {
    /**
     * Clear the make factory
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return mixed
     */
    function make(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->make($attribute);
    }
}

if (!function_exists('raw')) {
    /**
     * Clear the raw factory
     *
     * @param string $class
     * @param array $attribute
     * @param null $times
     * @return mixed
     */
    function raw(string $class, array $attribute = [], $times = null)
    {
        return factory($class, $times)->raw($attribute);
    }
}
