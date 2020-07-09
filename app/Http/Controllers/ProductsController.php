<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductType;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return view('app.products');
    }


    public function addNewProduct(Request $request)
    {
        $prodData = $request->input();
        $prod = new Product();

        $prodImage = $request->file('imageFile');
        if ($prodImage->isValid()) {

            $extension = $prodImage->extension();
            $prodImagePath = $prodImage->storeAs('prod-images', 'test.'.$extension);
        }
    }


    public function getProdTypesList()
    {
        return ProductType::all()->toJson();
    }
}
