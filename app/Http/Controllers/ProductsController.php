<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        return view('app.products');
    }


    public function addNew(Request $request) : int
    {
        try {

            $prodData = $request->input();
            $prod = Product::create($prodData);

            $prodType = ProductType::find((int) $prodData['typeId']);
            if (!empty($prodType)) {
                $prod->type()->associate($prodType);
            }

            $image = $request->file('imageFile');
            if (!empty($image)) {
                $imagePath = $this->saveImage($image, 'prod-images', "prod_{$prod->id}");
                $this->handleImage($imagePath, 'prod-images');
                $prod->image = $imagePath;
            }
            $prod->save();

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }

        return (int) $prod->id;
    }


    public function save(Request $request) : void
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
                $image = $request->file('imageFile');

                if (!empty($image)) {
                    $imagePath = $this->saveImage($image, 'prod-images', "prod_{$prodId}");
                    $this->handleImage($imagePath, 'prod-images');
                    $prod->image = $imagePath;
                }
            }

            $prod->update($prodData);
            $prod->save();

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }


    public function getList(Request $request) : array
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
            ->get()
            ->each(function ($item, $key) {
                $item->image_thumbs = $this->getImageThumbs("prod_{$item->id}", 'prod-images');
            });

        $pagesCount = (int) ceil($allResultsCount / $perPage);

        return [
            'items' => $results->toJson(),
            'pages_count' => $pagesCount,
        ];
    }


    public function getTypesList() : string
    {
        return ProductType::all()->toJson();
    }


    public function delete(Request $request) : void
    {
        try {

            $id = (int) $request->input('id');

            $prodImages = $this->getImagePathesList("prod_{$id}", 'prod-images');
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            Product::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }
}
