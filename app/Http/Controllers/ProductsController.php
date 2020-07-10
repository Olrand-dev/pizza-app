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
        $prod = Product::create($prodData);

        $prodType = ProductType::find((int) $prodData['typeId']);
        if (!empty($prodType)) {
            $prod->type()->associate($prodType);
        }

        $prodImage = $request->file('imageFile');
        if (!empty($prodImage) and $prodImage->isValid()) {

            $extension = $prodImage->extension();
            $prodImagePath = $prodImage->storeAs('prod-images', "prod_{$prod->id}.{$extension}", 'public');
            $prod->image = $prodImagePath;
        }
        $prod->save();

        return $prod->id;
    }


    public function getProdsList()
    {
        $list = Product::with('type')->get();

        /* foreach ($list as &$prod) {
            $prod->image_url = '123';
        } */

        return $list->toJson();
    }


    public function getProdTypesList()
    {
        return ProductType::all()->toJson();
    }
}
