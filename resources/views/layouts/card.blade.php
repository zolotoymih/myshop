<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <div class ="labels">
            @if ($product->isNew())
                <span class="badge badge-success"> Новинка</span>
            @endif

                @if ($product->isRecommend())
                <span class="badge badge-warning"> Рекомендуем</span>
                @endif
            @if ($product->isHit())
                        <span class="badge badge-danger"> Хит продаж!</span>
                @endif
        </div>
        <img src="{{Storage::url($product->image)}}" alt="{{$product->__('name')}}">
        <div class="caption">
            {{--<h3>iPhone X 64GB</h3>
            <p>71990 руб.</p>--}}
            <h3>{{$product->__('name')}}</h3>
            <p>{{$product->price}} {{App\Services\CurrencyConversion::getCurrencySymbol()}}</p>
            <p>
            <form action="{{route('basket-add', $product)}}" method="POST">
                {{--<a href="{{route('basket')}}" class="btn btn-primary" role="button">В корзину</a>--}}
                @if($product->isAvailable())
                    <button type="submit" class="btn btn-primary" role="button">В корзину</button>
                @else
                    Не доступен
                @endif
                    {{--  {{$product->getCategory()->name}} --}}
{{--                 {{$product->category->name}}--}}
                 {{-- @isset($category)
                      {{$category->name}}
                  @endisset --}}
{{--                <a href="{{route('product', [$product->category->code,$product->code])}}" class="btn btn-default"--}}
                <a href="{{route('product', [isset($category) ? $category->code : $product->category->code,$product->code])}}" class="btn btn-default"
                   role="button">Подробнее</a>
                @csrf
            </form>
{{--<a href="http://laravel-diplom-1.rdavydov.ru/mobiles/iphone_x_64" class="btn btn-default"
   role="button">Подробнее</a>--}}
</p>
</div>
</div>
</div>
