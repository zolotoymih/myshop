@extends('layouts.master')
@section('title', 'Все категории')
@section('content')
        @foreach($categories as $category)
            <div class="panel">
                {{-- <a href="/{{$category->code}}"> --}}
                <a href="{{route('category', $category->code)}}">
                <img src="{{Storage::url($category->image)}}">
                    <h2>{{$category->name}}</h2>
                </a>
                <p>
                    {{$category->description}}
                </p>
            </div>
            @endforeach
       {{-- <div class="panel">
            <a href="/mobiles">
                <img src="http://laravel-diplom-1.rdavydov.ru/storage/categories/mobile.jpg">
                <h2>Мобильные телефоны</h2>
            </a>
            <p>
                В этом разделе вы найдёте самые популярные мобильные телефонамы по отличным ценам!
            </p>
        </div>
        <div class="panel">
            <a href="/portable">
                <img src="http://laravel-diplom-1.rdavydov.ru/storage/categories/portable.jpg">
                <h2>Портативная техника</h2>
            </a>
            <p>
                Раздел с портативной техникой.
            </p>
        </div>
        <div class="panel">
            <a href="/appliances">
                <img src="http://laravel-diplom-1.rdavydov.ru/storage/categories/appliance.jpg">
                <h2>Бытовая техника</h2>
            </a>
            <p>
                Раздел с бытовой техникой
            </p>
        </div> --}}
@endsection
