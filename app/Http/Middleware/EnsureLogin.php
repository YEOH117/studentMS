<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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


        $response = $next($request);
        // 1. 如果用户未登录
        // 2. 并且访问的不是 登陆跟重置密码、验证码图片
        if (!$request->user()  && !$request->is('login','captcha/*','password/*')) {
            return redirect()->route('login');
        }


        return $response;

    }

}
