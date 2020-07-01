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
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'pe-7s-graph2',
                'url' => url('/'),
            ],
            [
                'name' => 'Products',
                'slug' => 'products',
                'icon' => 'pe-7s-box2',
                'url' => url('/products'),
            ],
        ];
    }
    
}
