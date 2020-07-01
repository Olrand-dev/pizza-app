<?php

namespace App\Http\Middleware;

use App\Consts\SidebarMenuConst;
use Closure;

class SetSessionPageData
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
        $menu = new SidebarMenuConst();
        $url = $request->url();

        foreach ($menu->elements as $e) {

            if ($e['url'] === $url) {
                $request->session()->put('page_slug', $e['slug']);
                $request->session()->put('page_name', $e['name']);
            }
        }
        return $next($request);
    }
}
