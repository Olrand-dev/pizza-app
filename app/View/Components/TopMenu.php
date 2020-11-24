<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class TopMenu extends Component
{

    /**
     * @var string
     */
    private $pageName;

    /**
     * @var array
     */
    private $userData;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->userData = Auth::user();
        }
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
            'user' => $this->userData,
        ]);
    }

}
