<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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


    public function save(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $data = $request->input();
            $customer = Customer::find((int) $data['id']);
            $user = User::find($customer->user->id);

            $customer->name = $data['name'];
            $customer->phone = $data['phone'];
            $customer->address = $data['address'];
            $customer->save();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();

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


    public function delete(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $id = (int) $request->input('id');

            $customer = Customer::find($id);
            User::destroy($customer->user->id);
            Customer::destroy($id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }
}
