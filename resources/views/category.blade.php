@extends('layouts.master')

@section('title', 'Категория' .$category->name)

@section('content')
        <h1>
            {{$category->name}} {{$category->products->count()}}
{{--  @if($category=='mobiles')
     Мобильные телефоны
 @elseif ($category=='portable')
     Портативная техника
 @elseif ($category=='appliances')
     Бытовая техника
 @endif --}}
</h1>
<p>
    {{$category->description}}
    {{-- В этом разделе вы найдёте самые популярные мобильные телефонамы по отличным ценам! --}}
</p>
        <div class="row">
            {{--@foreach($products as $product)--}}
            @foreach($category->products as $product)
{{--            @foreach($category->products->with('category')->get() as $product)--}}
                @include('layouts.card', compact('product')) {{-- , ['product' =>$product]  --}}
            @endforeach
            {{--@include('card', ['category' =>$category]) {{-- , ['product' =>$product]  --}}

        </div>

@endsection
