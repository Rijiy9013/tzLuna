<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $sent = $request->bearerToken();
        $expected = config('services.api.api_token');

        if (!$sent || !is_string($expected) || !hash_equals($expected, $sent)) {
            return response()
                ->json(['message' => 'Unauthorized'], 401)
                ->withHeaders(['WWW-Authenticate' => 'Bearer realm="api"']);
        }

        return $next($request);
    }
}
