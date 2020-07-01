<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TopMenu extends Component
{

    /**
     * @var string
     */
    private $pageName;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pageName = session('page_name', 'Dashboard');
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.top-menu', [
            'pageName' => $this->pageName,
        ]);
    }

}
