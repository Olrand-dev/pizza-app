<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Http\Requests\SaveOrder;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Model;
use App\Models\Order;
use App\Models\OrderArchived;
use App\Models\OrderStatus;
use App\Models\PizzaSet;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
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
        $this->authorize('viewAny', Order::class);
        return view('app.orders');
    }


    public function addNew(SaveOrder $request) : int
    {
        $this->authorize('create', Order::class);
        return $this->saveOrder($request, true);
    }


    public function save(SaveOrder $request) : void
    {
        $this->authorize('update', Order::class);
        $this->saveOrder($request);
    }


    public function saveOrder(FormRequest $request, bool $newOrder = false) : int
    {
        DB::beginTransaction();
        try {

            $orderData = $request->validated(); //dd($orderData);

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

            $customerId = $orderData['customer_id'];
            $order->customer()->associate(Customer::find($customerId));

            $order->save();

            //todo: временное решение, сделать механику комментариев
            $customerComment = $orderData['comments'][0];
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

            $this->setOrderStatus($orderId, $statusSlug);

        } catch(\Throwable $e) {

            abort(500, $e->getMessage());
        }
    }


    private function setOrderStatus(int $orderId, string $statusSlug) : void
    {
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
            $orderData = $this->getOrderFullData($orderId);

            $archived = new OrderArchived();
            $archived->order_id = $orderId;
            $archived->data = json_encode($orderData);
            $archived->save();
        }
    }


    public function employeeConnect(Request $request) : void
    {
        $orderId = (int) $request->input('id');
        $refuse = $request->input('refuse') === 'true';
        $user = Auth::user();
        $employee = $user->userable;

        if ($user->isCourier()) {
            $statusId = ($refuse) ? SystemConst::ORDER_STATUS_READY : SystemConst::ORDER_STATUS_DELIVERY;
            $this->setOrderStatus(
                $orderId,
                $this->getStatusSlug($statusId)
            );
        }

        $order = Order::find($orderId);
        if (!empty($order)) {

            if ($refuse) $order->employees()->detach($employee);
            else $order->employees()->attach($employee);
        }
    }


    public function orderDelivered(Request $request) : void
    {
        $orderId = (int) $request->input('id');
        $this->setOrderStatus(
            $orderId,
            $this->getStatusSlug(SystemConst::ORDER_STATUS_DELIVERED)
        );
    }


    private function getStatusSlug(int $statusId) : string
    {
        return SystemConst::ORDER_STATUSES_MAP[$statusId]['slug'];
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


    private function getOrderFullData(int $id) : array
    {
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


    public function getOrderData(Request $request) : array
    {
        $id = (int) $request->input('id');
        return $this->getOrderFullData($id);
    }


    public function getList(Request $request) : array
    {
        $input = $request->input();
        $user = Auth::user();

        $page = (int) $input['page'];
        $perPage = (int) $input['per_page'];
        $byStatus = (int) $input['by_status'];
        $findBy = $input['find_by'];
        $findQuery = $input['find_query'];
        $sortField = $input['sort_field'];
        $sortDirection = $input['sort_dir'];

        $query = Order::with(['status', 'customer', 'employees']);

        if ($byStatus > 0) {
            $query = $query->where('status_id', $byStatus);
        }

        if ($user->isChef() or $user->isCook()) {
            $query = $query->whereIn('status_id', [
                SystemConst::ORDER_STATUS_ACCEPTED,
                SystemConst::ORDER_STATUS_COOKING,
                SystemConst::ORDER_STATUS_READY,
            ]);
        }
        if ($user->isCourier()) {
            $query = $query->whereIn('status_id', [
                SystemConst::ORDER_STATUS_READY,
                SystemConst::ORDER_STATUS_DELIVERY,
                SystemConst::ORDER_STATUS_DELIVERED,
            ]);
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
            ->get()
            ->each(function ($item, $key) use ($user) {
                $connectStatus = 'denied';

                if (
                    (($user->isChef() or  $user->isCook()) and
                    $item->status->id === SystemConst::ORDER_STATUS_READY) or
                    ($user->isCourier() and $item->status->id === SystemConst::ORDER_STATUS_DELIVERED)
                ) {
                    return;
                }

                if (
                    $user->isChef() or
                    $user->isCook() or
                    $user->isCourier()
                ) {
                    $connectStatus = 'allowed';
                }
                foreach ($item->employees as $index => $employee) {
                    $employee->role_name = $employee->user->role->name;
                    $employee->role_slug = $employee->user->role->slug;

                    if ($employee->id === $user->userable->id) {
                       $connectStatus = ($user->isCourier()) ? 'courier_taken' : 'taken';
                    }
                }
                $item->connect_status = $connectStatus;
            });

        $pagesCount = (int) ceil($allResultsCount / $perPage);

        return [
            'items' => $results->toJson(),
            'pages_count' => $pagesCount,
        ];
    }


    public function delete(Request $request) : void
    {
        $this->authorize('forceDelete', Order::class);

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
