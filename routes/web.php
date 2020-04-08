<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//   return view('index');//  view -это хелпер открывает папку views
//}); переносим в MainController.php

//Route::get('/categories', function () {
//   return view('categories'); переносим в MainController.php
//});
//Route::get('/mobiles/iphone_x_64', function () {
//   return view('product'); переносим в MainController.php
//});
Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,

]);

Route::get('locale/{locale}', 'MainController@changeLocale')->name('locale');
Route::get('currency/{currencyCode}', 'MainController@changeCurrency')->name('currency');

Route::get('reset', 'ResetController@reset')->name('reset_db');

Route::get('/logout', 'Auth\LoginController@logout')->name('get-logout');

Route::middleware(['set_locale'])->group(function () {

    Route::middleware(['auth'])->group(function () {
        Route::group([
            'namespace' => 'Person',
            'prefix' => 'person',
            'as' => 'person.',
        ], function () {
            Route::get('/orders', 'OrderController@index')->name('orders.index');
            Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
        });


        Route::group([
//    'middleware' => 'auth',
            'namespace' => 'Admin',
            'prefix' => 'admin',
        ], function () {
            Route::group(['middleware' => 'is_admin'], function () {
                Route::get('/orders', 'OrderController@index')->name('home');
                Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
            });
//    Route::get('/home', 'HomeController@index')->name('home');
            Route::resource('categories', 'CategoryController');
            Route::resource('products', 'ProductController');
        });

    });

//Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

    Route::get('/', 'MainController@index')->name('index'); //метод index из MainController.php

    Route::get('/categories', 'MainController@categories')->name('categories'); //метод categories из MainController.php

    Route::post('subscription/{product}', 'MainController@subscribe')->name('subscription');

    Route::group([
        'prefix' => 'basket',
    ], function () {
        //Route::post('/add/{id}', 'BasketController@basketAdd')->name('basket-add');
        Route::post('/add/{product}', 'BasketController@basketAdd')->name('basket-add');

        Route::group([
            'middleware' => 'basket_not_empty',
        ], function () {
//    Route::get('/basket', 'BasketController@basket')->name('basket');
            Route::get('/', 'BasketController@basket')->name('basket');
            Route::get('/place', 'BasketController@basketPlace')->name('basket-place');
            //Route::post('/remove/{id}', 'BasketController@basketRemove')->name('basket-remove');
            Route::post('/remove/{product}', 'BasketController@basketRemove')->name('basket-remove');
            Route::post('/place', 'BasketController@basketConfirm')->name('basket-confirm');

        });
    });


    Route::get('/{category}', 'MainController@category')->name('category');
//Route::get('/mobiles/iphone_x_64', 'MainController@product'); //метод product из MainController.php

//Route::get('/mobiles/{product?}', 'MainController@product')->name('product'); //метод product из MainController.php
    Route::get('/{category}/{product?}', 'MainController@product')->name('product'); //метод product из MainController.php
//? -это значит не обязатальный елемент


});



