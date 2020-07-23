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


    public function addNewProd(Request $request)
    {
        try {

            $prodData = $request->input();
            $prod = Product::create($prodData);

            $prodType = ProductType::find((int) $prodData['typeId']);
            if (!empty($prodType)) {
                $prod->type()->associate($prodType);
            }

            $imagePath = $this->saveProdImage($request, $prod->id);
            $this->handleProdImage($imagePath);
            $prod->image = $imagePath;

            $prod->save();

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }

        return $prod->id;
    }


    public function saveProd(Request $request)
    {
        try {

            $prodData = $request->input();
            $prodId = (int) $prodData['id'];
            $prod = Product::find($prodId);

            if ($prod->type_id != $prodData['type_id']) {
                $prodType = ProductType::find((int) $prodData['type_id']);

                if (!empty($prodType)) {
                    $prod->type()->associate($prodType);
                }
            }

            if ($prodData['image_changed'] === 'true') {

                $imagePath = $this->saveProdImage($request, $prodId);
                $this->handleProdImage($imagePath);
                $prod->image = $imagePath;
            }

            $prod->update($prodData);
            $prod->save();

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }


    private function saveProdImage(Request $request, $prodId) 
    {
        $prodImage = $request->file('imageFile');
        $prodImagePath = '';

        if (!empty($prodImage) and $prodImage->isValid()) {

            $extension = $prodImage->extension();
            $prodImagePath = $prodImage->storeAs('prod-images', "prod_{$prodId}.{$extension}", 'public');
        }
        return $prodImagePath;
    }


    private function handleProdImage(string $imagePath)
    {
        $fullImagePath = storage_path('app/public/' . $imagePath);
        $this->resizeImage($fullImagePath);
        $this->makeImageThumbs(
            $fullImagePath,
            'public/prod-images/thumbs'
        );
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
