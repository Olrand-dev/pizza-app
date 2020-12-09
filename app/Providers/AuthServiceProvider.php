<?php

namespace App\Providers;

use App\Consts\SidebarMenuConst;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('show-sidebar', function ($user) {
            return (
                $user->isAdmin() or
                $user->isManager() or
                $user->isChef() or
                $user->isCook()
            );
        });

        $mainMenuElements = (new SidebarMenuConst())->elements;
        foreach ($mainMenuElements as $element) {
            Gate::define("show-mm-element-{$element['slug']}", function ($user) use ($element) {
                return Controller::checkPermission($user, $element['permissions']);
            });
        }
    }
}
