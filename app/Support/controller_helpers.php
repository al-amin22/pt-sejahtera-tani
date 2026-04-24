<?php

namespace App\Http\Controllers {
	if (! function_exists(__NAMESPACE__ . '\\view')) {
		function view($view = null, $data = [], $mergeData = [])
		{
			return \Illuminate\Support\Facades\View::make($view, $data, $mergeData);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\redirect')) {
		function redirect($to = null, $status = 302, $headers = [], $secure = null)
		{
			return \Illuminate\Support\Facades\Redirect::to($to, $status, $headers, $secure);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\response')) {
		function response($content = null, $status = 200, array $headers = [])
		{
			return \Illuminate\Support\Facades\Response::make($content, $status, $headers);
		}
	}

}

namespace App\Http\Controllers\Staff {
	if (! function_exists(__NAMESPACE__ . '\\view')) {
		function view($view = null, $data = [], $mergeData = [])
		{
			return \Illuminate\Support\Facades\View::make($view, $data, $mergeData);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\redirect')) {
		function redirect($to = null, $status = 302, $headers = [], $secure = null)
		{
			return \Illuminate\Support\Facades\Redirect::to($to, $status, $headers, $secure);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\response')) {
		function response($content = null, $status = 200, array $headers = [])
		{
			return \Illuminate\Support\Facades\Response::make($content, $status, $headers);
		}
	}

}

namespace App\Http\Controllers\Auth {
	if (! function_exists(__NAMESPACE__ . '\\view')) {
		function view($view = null, $data = [], $mergeData = [])
		{
			return \Illuminate\Support\Facades\View::make($view, $data, $mergeData);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\redirect')) {
		function redirect($to = null, $status = 302, $headers = [], $secure = null)
		{
			return \Illuminate\Support\Facades\Redirect::to($to, $status, $headers, $secure);
		}
	}

	if (! function_exists(__NAMESPACE__ . '\\response')) {
		function response($content = null, $status = 200, array $headers = [])
		{
			return \Illuminate\Support\Facades\Response::make($content, $status, $headers);
		}
	}

}
