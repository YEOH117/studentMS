<?php

namespace App\Http\Middleware;

use Closure;

class EnsureLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 1. 如果用户未登录
        // 2. 并且访问的不是 login
        if (!$request->user()  && !$request->is('login')) {
            // 根据客户端返回对应的内容
            return  redirect()->route('login');
        }
        return $next($request);
    }

}
