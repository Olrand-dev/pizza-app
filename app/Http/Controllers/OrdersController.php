<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Model;
use App\Models\Order;
use App\Models\OrderArchived;
use App\Models\OrderStatus;
use App\Models\PizzaSet;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public static $orderElements = [
        'pizza_sets' => [PizzaSet::class, 'pizzaSets'],
        'products' => [Product::class, 'products'],
    ];


    public function index()
    {
        return view('app.orders');
    }


    public function addNew(Request $request) : int
    {
        return $this->saveOrder($request, true);
    }


    public function save(Request $request) : void
    {
        $this->saveOrder($request);
    }


    public function saveOrder(Request $request, bool $newOrder = false) : int
    {
        DB::beginTransaction();
        try {

            $orderData = $request->input();

            $order = ($newOrder) ? new Order() : Order::find($orderData['id']);
            $order->cost = (float) $orderData['cost'];
            $order->weight = (int) $orderData['weight'];

            if ($newOrder) {
                $orderStatus = Auth::user()->isManager() ?
                    SystemConst::ORDER_STATUS_ACCEPTED :
                    SystemConst::ORDER_STATUS_NEW;

                $status = OrderStatus::find($orderStatus);
                $order->status()->associate($status);
            }

            $customerId = ($newOrder) ? $orderData['customer_id'] : $orderData['customer']['id'];
            $customer =  Customer::find($customerId);
            $order->customer()->associate($customer);

            $order->save();

            $customerComment = ($newOrder) ? $orderData['customer_comment'] : $orderData['comments'][0];
            if (empty($customerComment)) $customerComment = '';
            $comment = ($newOrder) ? new Comment() : Comment::find($customerComment['id']);
            $comment->content = ($newOrder) ? $customerComment : $customerComment['content'];
            if ($newOrder) $comment->commentable()->associate($order);
            $comment->save();

            foreach (self::$orderElements as $elementsSlug => $options) {

                if (!$newOrder) {
                    $relationName = $options[1];
                    $order->$relationName()->detach();
                }
                $this->attachOrderElements(
                    $order,
                    $orderData,
                    $elementsSlug,
                    $options
                );
            }

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return (int) $order->id;
    }


    private function attachOrderElements(
        Model $order,
        array $newOrderData,
        string $elementsSlug,
        array $options
    ) : void
    {
        $modelClass = $options[0];
        $relationName = $options[1];
        $elements = $newOrderData[$elementsSlug];

        foreach ($elements as $element) {
            $instance = $modelClass::find($element['id']);

            if (!empty($instance)) {
                $order->$relationName()->attach($instance->id, ['quantity' => $element['quantity']]);
            }
        }
    }


    public function setStatus(Request $request) : void
    {
        try {

            $orderId = (int) $request->input('order_id');
            $statusSlug = $request->input('status');

            $order = Order::find($orderId);
            $status = OrderStatus::where('slug', 'like', $statusSlug)->first();

            if (!empty($order) and !empty($status)) {
                $order->status()->associate($status);
            }
            $order->save();

            if (in_array($status->id, [
                SystemConst::ORDER_STATUS_DECLINED,
                SystemConst::ORDER_STATUS_ARCHIVED,
            ])) {
                $orderId = $order->id;
                $orderData = $this->getOrderFullData($request, $orderId);

                $archived = new OrderArchived();
                $archived->order_id = $orderId;
                $archived->data = json_encode($orderData);
                $archived->save();
            }

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }


    public function getDataLists(Request $request) : array
    {
        $pizzaSets = PizzaSet::all()->toArray();
        $addProds = Product::where('type_id', SystemConst::PRODUCT_TYPE_ADD_PRODUCTS)
            ->orderBy('name', 'desc')
            ->get()
            ->toArray();
        $orderStatuses = OrderStatus::all()->toArray();

        return [
            'pizza_sets_list' => $pizzaSets,
            'add_prods_list' => $addProds,
            'order_statuses_list' => $orderStatuses,
        ];
    }


    public function getOrderFullData(Request $request, int $id = 0) : array
    {
        if (empty($id)) $id = (int) $request->input('id', 0);
        if (empty($id)) return [];

        $data = Order::with(['products', 'pizzaSets', 'status', 'customer.user', 'comments'])
            ->where('id', $id)
            ->first();

        foreach ($data->products as &$product) {
            $product->quantity = $product->connection->quantity;
        }
        foreach ($data->pizzaSets as &$pizzaSet) {
            $pizzaSet->quantity = $pizzaSet->connection->quantity;
        }

        return $data->toArray();
    }


    public function getList(Request $request) : array
    {
        $input = $request->input();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $byStatus = (int) $input['by_status'];
        $findBy = $input['find_by'];
        $findQuery = $input['find_query'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Order::with(['status', 'customer']);

        if ($byStatus > 0) {
            $query = $query->where('status_id', $byStatus);
        }
        if ($findQuery !== '') {
            $query = $query->whereHas(
                'customer',
                function ($query) use ($findBy, $findQuery) {
                    return $query->where($findBy, 'like', "%{$findQuery}%");
                }
            );
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
            $order = Order::find($id);

            if (!empty($order)) {

                foreach ($order->comments as $comment) {
                    Comment::destroy($comment->id);
                }
                foreach (self::$orderElements as $elementsSlug => $options) {

                    $relationName = $options[1];
                    $order->$relationName()->detach();
                }
                Order::destroy($id);
            }

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }
        DB::commit();
    }
}
