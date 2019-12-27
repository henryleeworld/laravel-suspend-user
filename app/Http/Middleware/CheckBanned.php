<?php

namespace App\Http\Middleware;

use Closure;

class CheckBanned
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
        if (auth()->check() && auth()->user()->banned_until && now()->lessThan(auth()->user()->banned_until)) {
            $banned_days = now()->diffInDays(auth()->user()->banned_until);
            auth()->logout();

            if ($banned_days > 14) {
                $message = '你的帳號已經被暫停了，請聯絡系統管理員。';
            } else {
                $message = '你的帳號已經被暫停 '.$banned_days.' 天，請聯絡系統管理員。';
            }

            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
