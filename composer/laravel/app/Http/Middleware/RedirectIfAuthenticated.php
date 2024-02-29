<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        # 中介層判定
        if(true)
        {
            //預設不允許存取
            $is_allow = false;
            //取得會員編號
            $user_id = session()->get('user_id');
            if(!is_null($user_id))
            {
                //已登入，允許存取
                $is_allow = true;
            }

            if(!$is_allow)
            {
                //若不允許存取，重新導向至首頁
                return redirect()->to('/');
            }
        }

        return $next($request);
    }
}
