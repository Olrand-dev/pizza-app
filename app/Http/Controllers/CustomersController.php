<?php

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomersController extends Controller
{
    public function index()
    {
        return view('app.customers');
    }


    public function addNew(Request $request) : int
    {
        DB::beginTransaction();
        try {

            $data = $request->input();

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make('temp_pass');
            $user->save();

            $customer = new Customer();
            $customer->name = $data['name'];
            $customer->phone = $data['phone'];
            $customer->address = $data['address'];
            $customer->save();

            $customer->user()->save($user);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return $customer->id;
    }


    /*public function save(Request $request) : void
    {
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

            $updatedSet = PizzaSet::find($setId);
            if ($updatedSet->weight !== $set->weight) {
                //todo: добавить пересчет веса заказов
            }

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }*/


    public function getList(Request $request) : array
    {
        $input = $request->input();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $findBy = $input['find_by'];
        $findQuery = $input['find_query'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Customer::with('user');

        if ($findQuery !== '') {
            $query = $query->where($findBy, 'like', "%{$findQuery}%");
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


    /*public function delete(Request $request) : void
    {
        try {

            $id = (int) $request->input('id');

            $instance = PizzaSet::find($id);
            $instance->products()->detach();

            $prodImages = $this->getImagePathesList("pizza_set_{$id}", $this->imagesDir);
            if (!empty($prodImages)) {
                Storage::delete($prodImages);
            }

            PizzaSet::destroy($id);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }*/
}
