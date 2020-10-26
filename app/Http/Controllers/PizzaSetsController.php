<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PizzaSetsController extends Controller
{
    public function index()
    {
        return view('app.pizza-sets');
    }
}
