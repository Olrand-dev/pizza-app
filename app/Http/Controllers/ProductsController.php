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
        try {

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

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }

        return $prod->id;
    }


    public function getProdsList(Request $request)
    {
        $input = $request->input();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $byType = (int) $input['by_type'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Product::with('type');
        if ($byType > 0) {
            $query = $query->where('type_id', $byType);
        }
        $query = $query->orderBy($sortField, $sortDirection);

        $allResultsCount = $query->get()->count();
        $results = $query
            ->offset($perPage * ($page - 1))
            ->limit($perPage)
            ->get();

        $pagesCount = (int) ceil($allResultsCount / $perPage);

        return [
            'items' => $results->toJson(),
            'pages_count' => $pagesCount,
        ];
    }


    public function getProdTypesList()
    {
        return ProductType::all()->toJson();
    }


    public function deleteProd(Request $request)
    {
        try {

            $id = (int) $request->input('id');
            Product::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }
}
