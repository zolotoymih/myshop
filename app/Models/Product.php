<?php

namespace App\Models;

use App\Models\Traits\Translatable;
use App\Services\CurrencyConversion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, Translatable;
//    use SoftDeletes;//эта фигня оставляет товар или.. не удаленным в таблице при удалении на сайте
    //public function getCategory()
//   {
  // //$category = Category::where('id', $this->category_id)->first(); //get() получаем все категории
 //       return Category::find($this->category_id);
 //       //dd($category);

  //  }
    protected $fillable = [
        'name', 'code', 'price', 'category_id', 'description', 'image', 'new', 'hit', 'recommend', 'count',
        'name_en','description_en',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);

    }//

    public function getPriceForCount()  //($count)
    {
        if(!is_null($this->pivot)){
            return  $this->pivot->count * $this->price;
        }
        return $this->price;
        //return $this->price * $count;

    }

    public function scopeByCode($query, $code){
        return $query->where('code', $code);
    }

    public function scopeHit($query){
        return $query->where('hit', 1);
    }

    public function scopeNew($query){
        return $query->where('new', 1);
    }

    public function scopeRecommend($query){
        return $query->where('recommend', 1);
    }


    public function setNewAttribute($value){
        $this->attributes['new'] = $value === 'on' ? 1 : 0;
    }
    public function setHitAttribute($value){
        $this->attributes['hit'] = $value === 'on' ? 1 : 0;
    }
    public function setRecommendAttribute($value){
        $this->attributes['recommend'] = $value === 'on' ? 1 : 0;
    }
    public function isAvailable(){
        return  !$this->trashed() && $this->count >0;
    }

    public function isHit(){
      return $this->hit ===1;
    }

    public function isNew(){
        return $this->new ===1;
    }

    public function isRecommend(){
        return $this->recommend ===1;
    }
 public function getPriceAttribute($value)
 {
     return round(CurrencyConversion::convert($value), 2); //round округление
 }

}
