<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployee;
use App\Http\Requests\SaveEmployee;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Employee::class);
        return view('app.employees');
    }


    public function addNew(CreateEmployee $request) : int
    {
        $this->authorize('create', Employee::class);
        return $this->saveEmployee($request, true);
    }


    public function save(SaveEmployee $request) : void
    {
        $this->authorize('update', Employee::class);
        $this->saveEmployee($request);
    }


    public function saveEmployee(FormRequest $request, bool $newUser = false) : int
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();
            $employee = ($newUser) ? new Employee() : Employee::find((int) $data['id']);
            $user = ($newUser) ? new User() : User::find($employee->user->id);

            $user->name = $data['name'];
            $user->email = $data['email'];

            $role = Role::find((int) $data['role_id']);
            if (!empty($role)) {
                $user->role()->associate($role);
            }

            $user->save();

            $password = $data['password'];
            if ($newUser or (!$newUser and !empty($password))) {
                self::makeUserPassword($user->id, $data['password']);
            }

            $employee->name = $data['name'];
            $employee->phone = $data['phone'];
            $employee->address = $data['address'];

            $employee->save();

            if ($newUser) $employee->user()->save($user);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return $employee->id;
    }


    public function getList(Request $request) : array
    {
        $input = $request->input();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $byRole = (int) $input['by_role'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Employee::with(['user', 'user.role']);

        if ($byRole > 0) {
            $query = $query->whereIn('id', function ($query) use ($byRole) {
                $this->getUsersIdByRole($query, $byRole);
            });
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


    public function delete(Request $request)
    {
        $this->authorize('forceDelete', Employee::class);

        $id = (int) $request->input('id');
        $check = $this->checkEmployeeCanBeDeleted($id);
        if (!$check['result']) {
            return $this->ajaxError($check['errorMsg']);
        }

        DB::beginTransaction();
        try {

            $employee = Employee::find($id);
            User::destroy($employee->user->id);
            Employee::destroy($id);

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }


    private function checkEmployeeCanBeDeleted(int $id) : array
    {
        $employeesId = DB::table('employee_order')
            ->whereIn('order_id', function ($query) {
                $this->getActiveOrdersId($query);
            })
            ->distinct()
            ->pluck('employee_id');

        $error = 'Employee attached to active orders cannot be removed.';

        return $this->checkCanBeDeleted($employeesId, $id, $error);
    }
}
