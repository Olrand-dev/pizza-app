<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\PizzaSet;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PizzaSetsController extends Controller
{
    private $imagesDir = 'pizza-set-images';


    public function index()
    {
        return view('app.pizza-sets');
    }


    public function addNew(Request $request) : int
    {
        try {

            $setData = $request->input(); //dd($setData);
            $ingredients = json_decode($setData['ingredients'], true);
            $ingredients[] = [
                'typeId' => SystemConst::PRODUCT_TYPE_PIZZA_BASE,
                'prodId' => $setData['baseId'],
                'quantity' => 1,
            ];
            $set = PizzaSet::create(['name' => $setData['name']]);

            foreach ($ingredients as $ingredient) {
                $prodId = (int) $ingredient['prodId'];
                $set->products()->attach($prodId, ['quantity' => $ingredient['quantity']]);
            }

            $image = $request->file('imageFile');
            if (!empty($image)) {
                $imagePath = $this->saveImage($image, $this->imagesDir, "pizza_set_{$set->id}");
                $this->handleImage($imagePath, $this->imagesDir);
                $set->image = $imagePath;
            }

            $set->save();

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }

        return (int) $set->id;
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
                    $imagePath = $this->saveImage($image, $this->imagesDir, "pizza_set_{$prodId}");
                    $this->handleImage($imagePath, $this->imagesDir);
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
                $item->image_thumbs = $this->getImageThumbs("pizza_set_{$item->id}", $this->imagesDir);
            });

        $pagesCount = (int) ceil($allResultsCount / $perPage);

        return [
            'items' => $results->toJson(),
            'pages_count' => $pagesCount,
        ];
    }


    public function getProdsList() : array
    {
        $list = [
            'ing_types_list' => [],
            'bases_list' => [],
            'ingredients_list' => [],
        ];

        Product::with('type')->get()->map(function ($product) use (&$list) {
            $typeId = $product->type_id;
            $ingredientsList = &$list['ingredients_list'];
            $ingTypesList = &$list['ing_types_list'];

            if ($typeId === SystemConst::PRODUCT_TYPE_ADD_PRODUCTS) return;
            $prodArr = json_decode((string) $product, true);

            switch ($typeId) {
                case SystemConst::PRODUCT_TYPE_PIZZA_BASE: {
                    $list['bases_list'][] = $prodArr;
                    break;
                }
                default: {
                    if (empty($ingredientsList[$typeId])) $ingredientsList[$typeId] = [];
                    if (empty($ingTypesList[$typeId])) $ingTypesList[$typeId] = [
                        'id' => $typeId,
                        'name' => $product->type->name,
                    ];
                    $ingredientsList[$typeId][] = $prodArr;
                }
            }
        });

        return $list;
    }


    public function delete(Request $request) : void
    {
        try {

            $id = (int) $request->input('id');

            $prodImages = $this->getImagePathesList("pizza_set_{$id}", $this->imagesDir);
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            Product::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }
}
