<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\PizzaSet;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PizzaSetsController extends Controller
{
    protected $imagesDir = 'pizza-set-images';


    public function index()
    {
        return view('app.pizza-sets');
    }


    public function addNew(Request $request) : int
    {
        DB::beginTransaction();
        try {

            $setData = $request->input();
            $ingredients = json_decode($setData['ingredients'], true);
            $this->addBaseToIngredients((int) $setData['base_id'], $ingredients);
            $set = PizzaSet::create(['name' => $setData['name']]);

            $this->attachProdsToSet($set, $ingredients);

            $image = $request->file('image_file');
            if (!empty($image)) {
                $this->handleRequestImageFile($set, $image, "pizza_set_{$set->id}");
            }

            $set->save();
            $this->calculatePizzaSet($set->id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return (int) $set->id;
    }


    public function save(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $setData = $request->input();
            $setId = (int) $setData['id'];
            $ingredients = json_decode($setData['ingredients'], true);
            $this->addBaseToIngredients((int) $setData['base_id'], $ingredients);
            $set = PizzaSet::find($setId);

            $set->products()->detach();
            $this->attachProdsToSet($set, $ingredients);

            if ($setData['image_changed'] === 'true') {
                $image = $request->file('image_file');

                if (!empty($image)) {
                    $this->handleRequestImageFile($set, $image, "pizza_set_{$setId}");
                }
            }

            $set->update($setData);
            $this->calculatePizzaSet($set->id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }


    private function addBaseToIngredients(int $baseId, array &$ingredients) : void
    {
        $ingredients[] = [
            'type_id' => SystemConst::PRODUCT_TYPE_PIZZA_BASE,
            'prod_id' => $baseId,
            'quantity' => 1,
        ];
    }


    private function attachProdsToSet(PizzaSet &$set, array $prods) : void
    {
        foreach ($prods as $prod) {
            $prodId = (int) $prod['prod_id'];
            $quantity = (int) $prod['quantity'];

            $set->products()->attach($prodId, ['quantity' => $quantity]);
        }
    }


    public function getList(Request $request) : array
    {
        $input = $request->input();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = PizzaSet::with('products');
        $query = $query->orderBy($sortField, $sortDirection);

        $allResultsCount = $query->get()->count();
        $results = $query
            ->offset($perPage * ($page - 1))
            ->limit($perPage)
            ->get()
            ->each(function ($item, $key) {
                $ingredients = [];

                foreach ($item->products as $index => $product) {
                    if ($product->type_id === SystemConst::PRODUCT_TYPE_PIZZA_BASE) {
                        $item->base_id = $product->id;
                    } else {
                        $ingredients[] = [
                            'type_id' => $product->type_id,
                            'prod_id' => $product->id,
                            'quantity' => $product->connection->quantity,
                        ];
                    }
                }
                $item->ingredients = $ingredients;
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


    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = (int) $request->input('id');
            $check = $this->checkPizzaSetCanBeDeleted($id);
            if (!$check['result']) {
                return $this->ajaxError($check['errorMsg']);
            }

            $instance = PizzaSet::find($id);
            $instance->products()->detach();

            $prodImages = $this->getImagePathesList("pizza_set_{$id}", $this->imagesDir);
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            PizzaSet::destroy($id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }


    private function checkPizzaSetCanBeDeleted(int $id) : array
    {
        $check = true;

        $setsId = DB::table('order_pizzaset')
            ->whereIn('order_id', function ($query) {
                $this->getActiveOrdersId($query);
            })
            ->distinct()
            ->pluck('pizzaset_id');

        $error = 'Pizza sets that are part of active orders cannot be removed.';

        foreach ($setsId as $setId) {
            if ($setId === $id) {
                $check = false;
            }
        }
        return [
            'result' => $check,
            'errorMsg' => $error,
        ];
    }
}
