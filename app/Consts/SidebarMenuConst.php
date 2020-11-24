<?php

namespace App\Consts;

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
            ],
            [
                'name' => 'Pizza Sets',
                'slug' => 'pizza-sets',
                'icon' => 'pe-7s-graph',
                'url' => url('/pizza-sets'),
            ],
            [
                'name' => 'Orders',
                'slug' => 'orders',
                'icon' => 'pe-7s-cart',
                'url' => url('/orders'),
            ],
            [
                'name' => 'Customers',
                'slug' => 'customers',
                'icon' => 'pe-7s-user',
                'url' => url('/customers'),
            ],
            [
                'name' => 'Employees',
                'slug' => 'employees',
                'icon' => 'pe-7s-users',
                'url' => url('/employees'),
            ],

        ];
    }

}
