<?php

namespace Illuminate\Http;

class Request
{
    /**
     * @param mixed $default
     */
    public function input($key = null, $default = null)
    {
        return $default;
    }

    /**
     * @param mixed $default
     */
    public function get($key = null, $default = null)
    {
        return $default;
    }

    public function hasFile($key)
    {
        return false;
    }

    public function file($key, $default = null)
    {
        return $default;
    }
}
