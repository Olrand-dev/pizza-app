<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\Model;
use App\Models\Order;
use App\Models\PizzaSet;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
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


    protected function makeUserPassword(int $userId, string $pass) : void
    {
        $user = User::find($userId);
        $user->password = Hash::make($pass);
        $user->save();
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
