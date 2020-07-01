<?php

namespace App\View\Components;

use App\Consts\SidebarMenuConst;
use Illuminate\View\Component;

class SidebarMenu extends Component
{

    /**
     * @var array
     */
    private $menuElements;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $menu = new SidebarMenuConst();
        $this->menuElements = $menu->elements;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.sidebar-menu', [
            'elements' => $this->menuElements,
        ]);
    }
    
}
