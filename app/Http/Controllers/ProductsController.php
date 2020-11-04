<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    protected $imagesDir = 'prod-images';


    public function index()
    {
        return view('app.products');
    }


    public function addNew(Request $request) : int
    {
        try {

            $prodData = $request->input();
            $prod = Product::create($prodData);

            $prodType = ProductType::find((int) $prodData['type_id']);
            if (!empty($prodType)) {
                $prod->type()->associate($prodType);
            }

            $image = $request->file('image_file');
            if (!empty($image)) {
                $this->handleRequestImageFile($prod, $image, "prod_{$prod->id}");
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

            $costChanged = (float) $prodData['cost'] !== $prod->cost;
            $weightChanged = (int) $prodData['weight'] !== $prod->weight;

            if ($prod->type_id != $prodData['type_id']) {
                $prodType = ProductType::find((int) $prodData['type_id']);

                if (!empty($prodType)) {
                    $prod->type()->associate($prodType);
                }
            }

            if ($prodData['image_changed'] === 'true') {
                $image = $request->file('image_file');

                if (!empty($image)) {
                    $this->handleRequestImageFile($prod, $image, "prod_{$prodId}");
                }
            }

            $prod->update($prodData);

            if ($costChanged or $weightChanged) {
                foreach ($prod->sets as $set) {
                    $this->calculatePizzaSet($set->id);
                }
            }
            if ($weightChanged) {
                //todo: добавить пересчет веса заказов
            }

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
                $item->image_thumbs = $this->getImageThumbs("prod_{$item->id}", $this->imagesDir);
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


    public function delete(Request $request)
    {
        $id = (int) $request->input('id');
        if (!$this->checkProductCanBeDeleted($id)) {
            return $this->ajaxError('Products that are part of pizza sets cannot be removed.');
        }

        try {

            $prodImages = $this->getImagePathesList("prod_{$id}", $this->imagesDir);
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            Product::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }


    private function checkProductCanBeDeleted(int $id) : bool
    {
        $check = true;

        $setProds = DB::table('pizzaset_product')->pluck('product_id');
        foreach ($setProds as $prodId) {
            if ($prodId === $id) {
                $check = false;
            }
        }
        return $check;
    }
}
