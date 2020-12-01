<?php

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Http\Requests\SaveCustomer;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index()
    {
        return view('app.customers');
    }


    public function addNew(SaveCustomer $request) : int
    {
        return $this->saveCustomer($request, true);
    }


    public function save(SaveCustomer $request) : void
    {
        $this->saveCustomer($request);
    }


    public function saveCustomer(FormRequest $request, bool $newUser = false) : int
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();

            $customer = ($newUser) ? new Customer() : Customer::find((int) $data['id']);
            $user = ($newUser) ? new User() : User::find($customer->user->id);

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();
            if ($newUser) $this->makeUserPassword($user->id, 'temp_pass');

            $customer->name = $data['name'];
            $customer->phone = $data['phone'];
            $customer->address = $data['address'];
            $customer->save();
            if ($newUser) $customer->user()->save($user);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return $customer->id;
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


    public function delete(Request $request)
    {
        $id = (int) $request->input('id');
        $check = $this->checkCustomerCanBeDeleted($id);
        if (!$check['result']) {
            return $this->ajaxError($check['errorMsg']);
        }

        DB::beginTransaction();
        try {

            $customer = Customer::find($id);
            User::destroy($customer->user->id);
            Customer::destroy($id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }


    private function checkCustomerCanBeDeleted(int $id) : array
    {
        $customersId = DB::table('orders')
            ->whereNotIn('status_id', [
                SystemConst::ORDER_STATUS_DECLINED,
                SystemConst::ORDER_STATUS_ARCHIVED
            ])
            ->groupBy('customer_id')
            ->pluck('customer_id'); //dd($customersId);

        $error = 'Customer attached to active orders cannot be removed.';

        return $this->checkCanBeDeleted($customersId, $id, $error);
    }
}
