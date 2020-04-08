<?php


namespace App\Classes;


use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Basket
{
    protected $order;

    /**
     * Basket constructor.
     * @param bool $createOrder
     */
    public function __construct($createOrder = false)
    {
        $orderId = session('orderId');

        if (is_null($orderId) && $createOrder) {
            $data = [];
            if (Auth::check()) {
//                $this->order->user_id = Auth::id();
//                $this->order->save();
                $data['user_id'] = Auth::id();
            }

            // $order = Order::create()->id; //->id походу ненадо
            $this->order = Order::create($data);
            //  dump($order->id);
            session(['orderId' => $this->order->id]);
        } else {
            $this->order = Order::findOrFail($orderId);
        }


    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function countAvailable($updateCount = false)
    {
        foreach ($this->order->products as $orderProduct) {
            if ($orderProduct->count < $this->getPivotRow($orderProduct)->count) {
                return false;
            }
            If ($updateCount) {
                $orderProduct->count -= $this->getPivotRow($orderProduct)->count;
            }
        }
        If ($updateCount) {
            $this->order->products->map->save();
        }

        return true;
    }

    public function saveOrder($name, $phone, $email)
    {
        if (!$this->countAvailable(true)) {
            return false;
        }
        Mail::to($email)->send(new OrderCreated($name, $this->getOrder() ));
        return $this->order->saveOrder($name, $phone);

    }

    protected function getPivotRow($product)
    {
        return $this->order->products()->where('product_id', $product->id)->first()->pivot;

    }

    public function removeProduct(Product $product)
    {
        if ($this->order->products->contains($product->id)) {
            // dd('yes');
            //$pivotRow = $this->order->products()->where('product_id', $product->id)->first()->pivot;
            $pivotRow = $this->getPivotRow($product);
            if ($pivotRow->count < 2) {
                $this->order->products()->detach($product->id);
            } else {
                $pivotRow->count--;
                $pivotRow->update(); //обновляем таблицу

            }
        }
        Order::changeFullSum(-$product->price);
    }

    public function addProduct(Product $product)
    {
        if ($this->order->products->contains($product->id)) { //$productId и дальше везде заменяем
            // dd('yes');
            $pivotRow = $this->getPivotRow($product);
            //$pivotRow = $this->order->products()->where('product_id', $product->id)->first()->pivot;
            $pivotRow->count++;
            If ($pivotRow->count > $product->count) {
                return false;
            }
            $pivotRow->update(); //обновляем таблицу
        } else {
            If ($product->count == 0) {
                return false;
            }
            $this->order->products()->attach($product->id);
        }


        Order::changeFullSum($product->price);
    }

}
