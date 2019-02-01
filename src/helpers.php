<?php

if(! function_exists("base_path")){

    /**
     * Get the base path
     *
     * @param null $path
     * @return string
     */
    function base_path($path = null)
    {
        return getcwd() . "/" . trim($path, "/");
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('dd')) {

    /**
     * Die and dump the given array
     *
     * @return void
     */
    function dd()
    {
        array_map(function($attributes) {
            dump($attributes);
        }, func_get_args());
        die;
    }
}