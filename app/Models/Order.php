<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\HttpCache\Ssi;

class Order extends Model
{
    protected $fillable=['user_id'];
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps(); //withTimestamps -єто обновление колонок в таблице create_at update_at
    }

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function calculateFullSum()
    {
        $sum = 0;
//        foreach ($this->products as $product) {
            foreach ($this->products()->withTrashed()->get() as $product) {
                $sum += $product->getPriceForCount();
        }
        return $sum;
    }

    public static function eraseOrderSum()
    {
        session()->foget('full_order_sum');
    }

    public static function changeFullSum($changeSum)
    {
        $sum = self::getFullSum() + $changeSum;
        session(['full_order_sum' => $sum]);
    }

    public static function getFullSum()
    {
        return session('full_order_sum', 0);
    }

    public function saveOrder($name, $phone)
    {
        if ($this->status == 0) {
            $this->name = $name;
            $this->phone = $phone;
            $this->status = 1;
            $this->save();
            session()->forget('orderId'); //очищаем сесию
            return true;
        } else {
            return false;
        }

    }
}
