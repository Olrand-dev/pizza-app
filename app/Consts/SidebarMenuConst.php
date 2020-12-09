<?php

namespace App\Consts;

use App\Http\Controllers\Controller;

class SidebarMenuConst
{

    /**
     * @var array
     */
    public $elements;


    public function __construct()
    {
        $this->elements = [
            /*[
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'pe-7s-graph2',
                'url' => url('/'),
            ],*/
            [
                'name' => 'Products',
                'slug' => 'products',
                'icon' => 'pe-7s-box2',
                'url' => url('/products'),
                'permissions' => Controller::getUserPermissionsMap(1, 1),
            ],
            [
                'name' => 'Pizza Sets',
                'slug' => 'pizza-sets',
                'icon' => 'pe-7s-graph',
                'url' => url('/pizza-sets'),
                'permissions' => Controller::getUserPermissionsMap(1, 1, 1, 1),
            ],
            [
                'name' => 'Orders',
                'slug' => 'orders',
                'icon' => 'pe-7s-cart',
                'url' => url('/orders'),
                'permissions' => Controller::getUserPermissionsMap(
                    1,
                    1,
                    1,
                    1,
                    1
                ),
            ],
            [
                'name' => 'Customers',
                'slug' => 'customers',
                'icon' => 'pe-7s-user',
                'url' => url('/customers'),
                'permissions' => Controller::getUserPermissionsMap(1, 1),
            ],
            [
                'name' => 'Employees',
                'slug' => 'employees',
                'icon' => 'pe-7s-users',
                'url' => url('/employees'),
                'permissions' => Controller::getUserPermissionsMap(1),
            ],

        ];
    }

}
