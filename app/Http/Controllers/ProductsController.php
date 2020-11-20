<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
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
        DB::beginTransaction();
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
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return (int) $prod->id;
    }


    public function save(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $prodData = $request->input();
            $prodId = (int) $prodData['id'];
            $typeId = (int) $prodData['type_id'];
            $prod = Product::find($prodId);

            $costChanged = (float) $prodData['cost'] !== $prod->cost;
            $weightChanged = (int) $prodData['weight'] !== $prod->weight;

            if ($prod->type_id !== $typeId) {
                $prodType = ProductType::find($typeId);

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

            //если есть связи товара с активными заказами, пересчитать их вес
            if ($weightChanged and $typeId === SystemConst::PRODUCT_TYPE_ADD_PRODUCTS) {
                $ordersId = DB::table('order_product')
                    ->whereIn('order_id', function ($query) {
                        $this->getActiveOrdersId($query);
                    })
                    ->where('product_id', $prodId)
                    ->pluck('order_id');

                if (!empty($ordersId)) {
                    foreach ($ordersId as $id) {
                        $this->calculateOrder($id, OrdersController::$orderElements);
                    }
                }
            }

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
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
        $check = $this->checkProductCanBeDeleted($id);
        if (!$check['result']) {
            return $this->ajaxError($check['errorMsg']);
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


    private function checkProductCanBeDeleted(int $id) : array
    {
        $check = true;
        $prod = Product::find($id);
        $typeId = $prod->type_id;

        switch ($typeId) {
            case SystemConst::PRODUCT_TYPE_ADD_PRODUCTS: {
                $prodsId = DB::table('order_product')
                    ->whereIn('order_id', function ($query) {
                        $this->getActiveOrdersId($query);
                    })
                    ->distinct()
                    ->pluck('product_id');
                $error = 'Additional products that are part of active orders cannot be removed.';
                break;
            }
            default: {
                $prodsId = DB::table('pizzaset_product')->pluck('product_id');
                $error = 'Products that are part of pizza sets cannot be removed.';
            }
        }

        foreach ($prodsId as $prodId) {
            if ($prodId === $id) {
                $check = false;
            }
        }
        return [
            'result' => $check,
            'errorMsg' => $error,
        ];
    }
}
