<?php

namespace App\Http\Controllers;

use App\Classes\Basket;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket()
    {
        $order = (new Basket())->getOrder();
        $orderId = session('orderId');
        //if (!is_null($orderId)) {
          //  $order = Order::findOrFail($orderId);
        //}
        return view('basket', compact('order'));

    }


    public function basketConfirm(Request $request) //Request - это значит мы работаем с запросом
    {
//        $orderId = session('orderId');
////        if (is_null($orderId)) {
////            return redirect()->route('index');
////        }
//
//        //$order = Order::find($orderId);
//        $order = Order::findOrFail($orderId);

//        $order = (new Basket())->getOrder();
//        $success = $order->saveOrder($request->name, $request->phone);
        $email = Auth::check() ? Auth::user()->email : $request->email;
        $success = (new Basket())->saveOrder($request->name, $request->phone, $email);
        //dd($request->all());
        If($success){
            session()->flash('success', __('basket.you_order_confirmed'));
        } else{
            session()->flash('warning', 'Tовар не доступен для заказа в полном обьеме');

        }
        Order::eraseOrderSum();

        return view('order', compact('order'));

    }

    public function basketPlace()
    {
//        $orderId = session('orderId');
////        if (is_null($orderId)) {
////            return redirect()->route('index');
////        }
//        $order = Order::findOrFail($orderId);
        $basket = new Basket();
        $order = $basket->getOrder();
        if (!$basket->countAvailable()){
            session()->flash('warning', 'Tовар не доступен для заказа в полном обьеме');
return redirect()-> route ('basket');
        }
        return view('order', compact('order'));
    }

    //public function basketAdd($productId)
    public function basketAdd(Product $product)
    {
        $result = (new Basket(true))->addProduct($product);
        //dd($productId);


        If ($result){
            session()->flash('success', 'Добавлен товар '. $product->name);
        } else {
            session()->flash('warning', 'Tовар '. $product->name. 'в больше кол-ве не доступен для заказа');

        }

        return redirect()->route('basket');
        //return view('basket', compact('order'));

        //dump($order);
    }

    public function basketRemove(Product $product)
    {
        (new Basket())->removeProduct($product);
//        $basket = new Basket();
//        $order= $basket->getOrder();

//        $orderId = session('orderId');
////        if (is_null($orderId)) {
////            return redirect()->route('basket');
////            //     return view('basket', compact('order'));
////        }
//        $order = Order::findOrFail($orderId);

        session()->flash('warning', 'Удален товар '. $product->name);

        return redirect()->route('basket');
        //return view('basket', compact('order'));

    }
}
