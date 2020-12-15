<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Model;
use App\Models\Order;
use App\Models\PizzaSet;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Policies\CustomerPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\OrderPolicy;
use App\Policies\PizzaSetPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function saveImage(UploadedFile $image, string $path, string $name) : string
    {
        $imagePath = '';

        if (!empty($image) and $image->isValid()) {

            $extension = $image->extension();
            $imagePath = $image->storeAs($path, "{$name}.{$extension}", 'public');
        }
        return $imagePath;
    }


    protected function resizeImage(string $imagePath, int $maxWidth = 1200) : void
    {
        $image = Image::make($imagePath);
        $width = $image->width();

        if ($width > $maxWidth) {
            $image->widen($maxWidth)->save($imagePath);
        }
    }


    protected function handleImage(string $imagePath, string $dir) : void
    {
        $fullImagePath = storage_path("app/public/{$imagePath}");
        $this->resizeImage($fullImagePath);
        $this->makeImageThumbs(
            $fullImagePath,
            "public/{$dir}/thumbs"
        );
    }


    protected function makeImageThumbs(string $imagePath, string $thumbsDir, array $widthSet = []) : void
    {
        $_widthSet = (!empty($widthSet)) ? $widthSet : SystemConst::IMAGE_THUMB_SIZES;

        $image = Image::make($imagePath);
        $width = $image->width();

        $pathParts = explode('/', $imagePath);
        $imageFile = array_pop($pathParts);
        $imageFileParts = explode('.', $imageFile);

        $imageName = $imageFileParts[0];
        $extension = $imageFileParts[1];

        $targetPath = implode('/', $pathParts) . '/thumbs';
        Storage::makeDirectory($thumbsDir);

        foreach ($_widthSet as $targetWidth) {

            if ($width > $targetWidth) {

                $resizedImageName = "{$imageName}_w_{$targetWidth}";
                $_image = $image->widen($targetWidth);
                $_image->save("{$targetPath}/{$resizedImageName}.{$extension}");
            }
        }
    }


    /*
     * Получить все пути изображений, полные и превью, для обработки через код
     */
    protected function getImagePathesList(string $prefix, string $dir) : array
    {
        return array_filter(
            Storage::allFiles("public/{$dir}"),
            function ($filePath) use ($prefix) {
                return preg_match("#{$prefix}#u", $filePath);
            }
        );
    }


    /*
     * Получить список превью изображений, для отображения на странице
     */
    protected function getImageThumbs(string $prefix, string $dir) : array
    {
        $files = $this->getImagePathesList($prefix, $dir);
        $thumbs = [];

        foreach (SystemConst::IMAGE_THUMB_SIZES as $size) {
            $_thumbs = array_filter(
                $files,
                function ($filePath) use ($prefix, $size) {
                    return preg_match("#thumbs/{$prefix}_w_{$size}#u", $filePath);
                }
            );

            $thumbs["w_{$size}"] = (!empty($_thumbs)) ?
                asset(str_replace('public', 'storage', array_values($_thumbs)[0])) :
                asset('storage/system/no_photo_sm.png');
        }

        return $thumbs;
    }


    protected function handleRequestImageFile(Model &$item, UploadedFile $image, string $namePattern) : void
    {
        $images = $this->getImagePathesList($namePattern, $this->imagesDir);
        if (!empty($images)) {
            Storage::delete($images);
        }
        $imagePath = $this->saveImage($image, $this->imagesDir, $namePattern);
        $this->handleImage($imagePath, $this->imagesDir);
        $item->image = $imagePath;
    }


    protected function calculatePizzaSet(int $id) : void
    {
        $instance = PizzaSet::find($id);
        $cost = 0;
        $weight = 0;

        foreach ($instance->products as $product) {
            $quantity = $product->connection->quantity;

            $cost += $product->cost * $quantity;
            $weight += $product->weight * $quantity;
        }
        $instance->cost = $cost;
        $instance->weight = $weight;
        $instance->save();

        $ordersId = DB::table('order_pizzaset')
            ->whereIn('order_id', function ($query) {
                $this->getActiveOrdersId($query);
            })
            ->where('pizzaset_id', $id)
            ->pluck('order_id');
        if (!empty($ordersId)) {
            foreach ($ordersId as $orderId) {
                $this->calculateOrder($orderId, OrdersController::$orderElements);
            }
        }
    }


    protected function getActiveOrdersId(Builder $query) : Builder
    {
        $query->select('id')
            ->from('orders')
            ->whereNotIn('status_id', [
                SystemConst::ORDER_STATUS_DECLINED,
                SystemConst::ORDER_STATUS_ARCHIVED
            ]);
        return $query;
    }


    protected function getUsersIdByRole(Builder $query, int $roleId) : Builder
    {
        $query->select('userable_id')
            ->from('users')
            ->where('role_id', $roleId);
        return $query;
    }


    public static function getUserPermissionsMap(
        int $admin = 1,
        int $manager = 0,
        int $chef = 0,
        int $cook = 0,
        int $courier = 0,
        int $customer = 0
    ) : array
    {
        return [
            'admin' => $admin,
            'manager' => $manager,
            'cook' => $cook,
            'chef' => $chef,
            'courier' => $courier,
            'customer' => $customer,
        ];
    }


    public static function checkPermission(User $user, array $permissionsMap) : bool
    {
        $permission = $permissionsMap[$user->role->slug] ?? null;
        return !empty($permission) and $permission === 1;
    }


    public function getPermissionsList(Request $request) : array
    {
        $modelsMap = [
            'order' => [OrderPolicy::class, Order::class],
            'product' => [ProductPolicy::class, Product::class],
            'pizza_set' => [PizzaSetPolicy::class, PizzaSet::class],
            'customer' => [CustomerPolicy::class, Customer::class],
            'employee' => [EmployeePolicy::class, Employee::class],
        ];

        $modelSlug = $request->input('model');
        $policyClass = $modelsMap[$modelSlug][0] ?? '';
        if (empty($policyClass)) return [];
        $modelClass = $modelsMap[$modelSlug][1];

        $user = Auth::user();
        $list = get_class_methods($policyClass);
        if (empty($list)) return [];

        $permissions = [];

        foreach ($list as $slug) {
            $permissions[$slug] = $user->can($slug, $modelClass);
        }
        return $permissions;
    }


    protected function calculateOrder(int $id, array $orderElements) : void
    {
        $instance = Order::find($id);
        $weight = 0;

        foreach ($orderElements as $elementsData) {
            $relationName = $elementsData[1];

            foreach($instance->$relationName as $item) {
                $quantity = $item->connection->quantity;
                $weight += $item->weight * $quantity;
            }
        }
        $instance->weight = $weight;
        $instance->save();
    }


    protected function ajaxError(string $message) : string
    {
        return json_encode([
            'status' => 'error',
            'message' => $message,
        ]);
    }


    public static function createEmployee(array $data) : void
    {
        $employee = new Employee();
        $user =  new User();

        $user->name = $data['name'];
        $user->email = $data['email'];

        $role = Role::find((int) $data['role_id']);
        if (!empty($role)) {
            $user->role()->associate($role);
        }

        $user->save();
        self::makeUserPassword($user->id, $data['password']);

        $employee->name = $data['name'];
        $employee->phone = $data['phone'];
        $employee->address = $data['address'];

        $employee->save();
        $employee->user()->save($user);
    }


    public static function makeUserPassword(int $userId, string $pass) : void
    {
        $user = User::find($userId);
        $user->password = Hash::make($pass);
        $user->save();
    }


    protected function comparePasswords(string $plainPass, string $hashPass) : bool
    {
        return Hash::check($plainPass, $hashPass);
    }


    protected function checkCanBeDeleted(Collection $idSet, int $targetId, string $errorMsg) : array
    {
        $check = true;

        foreach ($idSet as $id) {
            if ($id === $targetId) {
                $check = false;
            }
        }
        return [
            'result' => $check,
            'errorMsg' => $errorMsg,
        ];
    }
}
