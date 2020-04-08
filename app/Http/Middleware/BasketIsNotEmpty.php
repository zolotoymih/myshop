<?php

namespace App\Http\Middleware;


use App\Models\Order;
use Closure;

class BasketIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $orderId = session('orderId');

        if (!is_null($orderId) && Order::getFullSum() > 0) {
//            if (!is_null($orderId)) {
//            $order = Order::findOrFail($orderId);
//            if($order->products->count()>0){
////                if($order->products->count()==0){
////                session()->flash('warning', "Ваша корзина пуста");
////                return redirect()->route('index');
            return $next($request);
        }


//    session()->forget('orderId');
        session()->flash('warning', 'Ваша корзина пуста');

        return redirect()->route('index');
    }
}
