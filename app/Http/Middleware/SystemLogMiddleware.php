<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class SystemLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(auth()->check() && !$request->is('logs*')) {

            $routeName = $request->route()?->getName() ?? 'unknown';

            LogHelper::log(
                $request->method(),
                $routeName,
                $request->path()
            );

        }

        return $response;
    }
}