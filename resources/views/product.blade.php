@extends('layouts.master')
@section('title', 'Товар')
@section('content')
       <h1>{{$product->name}}</h1>
        <h2>{{$product-> category->__('name')}}</h2>
        <p>Цена: <b>{{$product->price}}</b></p>
{{--        <img src="http://laravel-diplom-1.rdavydov.ru/storage/products/iphone_x.jpg">--}}
       <img src="{{Storage::url($product->image)}}">
       <p>{{$product->description}}</p>
          @if($product->isAvailable())
              <form action="{{route('basket-add', $product)}}" method="POST">
              <button type="submit" class="btn btn-success" role="button">Добавить в корзину</button>
                  @csrf
              </form>
          @else
              <span>Не доступен</span>
              <br>
              <span>Сообщить мне , когда товар появиться в наличии:</span>
              <div><span class="warning">
                  @if($errors->get('email'))
                      {!! $errors->get('email')[0]!!}
                      @endif
              </span></div>
              <form action="{{route('subscription', $product)}}" method="POST">
                  <input type="text" name="email"> </input>
              <button type="submit" >Отправить</button>
                  @csrf
              </form>

          @endif

@endsection
