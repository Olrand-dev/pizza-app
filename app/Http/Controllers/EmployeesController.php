<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeesController extends Controller
{
    public function index()
    {
        return view('app.employees');
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

            $employee = new Employee();
            $employee->name = $data['name'];
            $employee->phone = $data['phone'];
            $employee->address = $data['address'];

            $role = Role::find((int) $data['role_id']);
            if (!empty($role)) {
                $employee->role()->associate($role);
            }
            $employee->save();

            $employee->user()->save($user);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return $employee->id;
    }


    public function save(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $data = $request->input();
            $employee = Employee::find((int) $data['id']);
            $user = User::find($employee->user->id);

            $employee->name = $data['name'];
            $employee->phone = $data['phone'];
            $employee->address = $data['address'];
            $employee->save();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->save();

            if ($employee->role_id != $data['role_id']) {
                $role = Role::find((int) $data['role_id']);
                if (!empty($role)) {
                    $employee->role()->associate($role);
                    $employee->save();
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
        $byRole = (int) $input['by_role'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Employee::with(['user', 'role']);

        if ($byRole > 0) {
            $query = $query->where('role_id', $byRole);
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


    public function getRolesList() : string
    {
        return Role::all()->toJson();
    }


    public function delete(Request $request) : void
    {
        DB::beginTransaction();
        try {

            $id = (int) $request->input('id');

            $employee = Employee::find($id);
            User::destroy($employee->user->id);
            Employee::destroy($id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }
}
