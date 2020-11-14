<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SystemConst;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PizzaSet;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        return view('app.orders');
    }


    public function addNew(Request $request) : int
    {
        DB::beginTransaction();
        try {

            //todo: добавить проверку создающего заказ пользователя, от гостей и покупателей
            //ставить заказу статус нового, от менеджера - принятый

            $orderData = $request->input();
            $orderStatus = SystemConst::ORDER_STATUS_NEW;
            $status = OrderStatus::find($orderStatus);

            $order = new Order();
            $order->cost = (float) $orderData['cost'];
            $order->weight = (int) $orderData['weight'];

            $customer = Customer::find($orderData['customer_id']);
            $order->customer()->associate($customer);
            $order->status()->associate($status);

            $comment = new Comment();
            $comment->content = $orderData['customer_comment'];
            $order->save();
            $comment->commentable()->associate($order);
            $comment->save();

            foreach ($orderData['pizza_sets'] as $set) {
                $instance = PizzaSet::find($set['id']);
                if (!empty($instance)) {
                    $order->pizzasets()->attach($instance->id, ['quantity' => $set['quantity']]);
                }
            }
            foreach ($orderData['products'] as $prod) {
                $instance = Product::find($prod['id']);
                if (!empty($instance)) {
                    $order->products()->attach($instance->id, ['quantity' => $prod['quantity']]);
                }
            }

        } catch(\Throwable $e) {
            DB::rollBack();
            abort(500, $e->getMessage());
        }

        DB::commit();
        return (int) $order->id;
    }


    public function save(Request $request) : void
    {
        try {

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

        $query = Order::with(['products', 'pizzasets', 'status']);

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
            ->get()
            ->each(function ($item, $key) {

            });

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
