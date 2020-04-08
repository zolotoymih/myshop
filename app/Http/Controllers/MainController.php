<?php

namespace App\Http\Controllers;
//пространство имен

//use App\Category;
use App\Http\Requests\ProductsFilterRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
//use App\Product;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $request)
    {
        //Log::info($request->ip());
        //Log::channel('single')->debug($request->ip());
        //  \Debugbar::info($request);

        //return '';
        //Route::get('/', function () {
        //   return view('index');//  view -это хелпер открывает папку views
        //}); переносим в MainController.php

        //Route::get('/categories', function () {
        //   return view('categories'); переносим в MainController.php
        //});
        //Route::get('/mobiles/iphone_x_64', function () {
        //   return view('product'); переносим в MainController.php
        //});

        // $productsQuery = Product::query();
        $productsQuery = Product::with('category');
        if ($request->filled('price_from')) {
            $productsQuery->where('price', '>=', $request->price_from);
        }

        if ($request->filled('price_to')) {
            $productsQuery->where('price', '<=', $request->price_to);
        }
        foreach (['hit', 'new', 'recommend'] as $field) {
            if ($request->has($field)) { //scope находит
//                $productsQuery->where($field, 1);
                $productsQuery->$field();
            }
        }

//        dd(get_class_methods($request)); // так можно посмотреть все методы

        $products = $productsQuery->paginate(6)->withPath("?" . $request->getQueryString());
//        $products=Product::paginate(6); //         paginate это тоже что get только с количеством вывода  и появляеть странички метод link
//        $products=Product::simplePaginate(6); //         появляеть странички метод link  в виде next
//        $products=Product::get();
        return view('index', compact('products'));
    }

    public function categories()
    {
        $categories = Category::get();
        return view('categories', compact('categories'));
    }


    // public function category($category) {
    //  $categoryObject =  Category::where('code',$category )->first(); //идем в модель Category и ищем в запросе код
    //   // dd($categoryObject);

    public function category($code)
    {
        $category = Category::where('code', $code)->first(); //теперь в переменной лежит запись модели
        //$products=Product::get();
        //$products=Product::where('category_id',$category->id )->get();
        //return view('category', compact('category', 'products')); //как масивом передаем категорию
        return view('category', compact('category'));
        //dd($category);
    }


//    public function product($category, $product = null)  {
    public function product($category, $productCode)
    {
        $product = Product::withTrashed()->byCode($productCode)->firstOrFail(); //first()
        //dump($product); dump() - это хелпер и отображает содержимое переменной (не прекращает выполнение)
        //dd($product); dd() - это хелпер и отображает содержимое переменной(прекращает выполнение)
        //dd(request()); Переменные HTTP-запроса Ассоциативный массив (array), который по умолчанию содержит данные переменных $_GET, $_POST и $_COOKIE.
        //return view('product', ['product' => $product]); //передаем значение ввиде массива с ключем представления
        return view('product', compact(['product']));
    }
    //public function basket()  {
    //    return view('basket');
    //}
    // public function basketPlace()  {
    //    return view('order');
    // }

    public function subscribe(SubscriptionRequest $request, Product $product)
    {
        Subscription::create([
            'email' => $request->email,
            'Product_id' => $product->id,
        ]);

        return redirect()->back()->with('success', 'Спасибо, мы свяжемся с вами при поступлении товара');
    }

    public function changeLocale($locale)
    {
        $availableLocales = ['ru', 'en'];
        if (!in_array($locale, $availableLocales)) {
            session(['locale' => $locale]);
            $locale = config('app.locale');
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
        $currentLocale = App::getLocale();
        return redirect()->back();

    }

    public function changeCurrency($currencyCode)
    {
        $currency = Currency::byCode($currencyCode)->firstOrFail();
        session(['currency' => $currency->code]);
        return redirect()->back();
    }
}
