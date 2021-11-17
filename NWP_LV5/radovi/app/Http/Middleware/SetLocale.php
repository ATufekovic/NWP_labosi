<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->segment(1), array_keys(Config::get("languages")))) {
            URL::defaults(['locale' => $request->segment(1)]);
            App::setLocale($request->segment(1));
        } else {
            URL::defaults(['locale' => "en"]);
            App::setLocale("en");
        }
        return $next($request);
    }
}
