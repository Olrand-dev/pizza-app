<?php

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Product;
use App\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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


    private function handleProdImage($imagePath)
    {
        $fullImagePath = storage_path('app/public/' . $imagePath);
        $this->resizeImage($fullImagePath);
        $this->makeImageThumbs(
            $fullImagePath,
            'public/prod-images/thumbs'
        );
    }


    private function getProdImagePathesList($prodId)
    {
        return array_filter(
            Storage::allFiles('public/prod-images'), 
            function($filePath) use ($prodId) {
                return preg_match("#prod_{$prodId}#u", $filePath);
            }
        );
    }


    private function getProdImageThumbs($prodId)
    {
        $files = $this->getProdImagePathesList($prodId);
        $thumbs = [];

        foreach (SystemConst::IMAGE_THUMB_SIZES as $size) {
            $_thumbs = array_filter(
                $files, 
                function($filePath) use ($prodId, $size) {
                    return preg_match("#thumbs/prod_{$prodId}_w_{$size}#u", $filePath);
                }
            );

            $thumbs["w_{$size}"] = (!empty($_thumbs)) ? 
                asset(str_replace('public', 'storage', array_values($_thumbs)[0])) : 
                asset('storage/system/no_photo_sm.png');
        }

        return $thumbs;
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
            ->get()
            ->each(function($item, $key) {
                $item->image_thumbs = $this->getProdImageThumbs($item->id);
            });

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

            $prodImages = $this->getProdImagePathesList($id);
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            Product::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }
}
