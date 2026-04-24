<?php

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

if (! function_exists('view')) {
    function view($view = null, $data = [], $mergeData = []): ViewContract
    {
        return app('view')->make($view, $data, $mergeData);
    }
}

if (! function_exists('redirect')) {
    function redirect($to = null, $status = 302, $headers = [], $secure = null): RedirectResponse
    {
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (! function_exists('response')) {
    function response($content = null, $status = 200, array $headers = [])
    {
        return app('response')->make($content, $status, $headers);
    }
}

if (! function_exists('now')) {
    function now($tz = null): Carbon
    {
        return Carbon::now($tz);
    }
}

if (! function_exists('collect')) {
    function collect($value = []): Collection
    {
        return new Collection($value);
    }
}

if (! function_exists('public_path')) {
    function public_path($path = ''): string
    {
        return app(Application::class)->publicPath($path);
    }
}
