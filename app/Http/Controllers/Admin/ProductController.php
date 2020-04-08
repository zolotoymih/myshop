<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('auth.products.index', compact('products'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        return view('auth.products.form', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $params = $request->all();
        unset($params['image']);
        If ($request->has('image')){
            $path = $request->file('image')->store('products'); //в теге атрибут image
            $params['image'] = $path;

        }

//        foreach (['new', 'hit', 'recommend'] as $fielName){
//            if (isset($params[$fielName])){
//                $params[$fielName] = 1;
//            }
//        }

       Product::create($params); //Category::create($request->all());
        //Product::create($request->all());
        return redirect()->route('products.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('auth.products.show', compact('product'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::get();
        return view('auth.products.form', compact('product', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $params = $request->all();
        unset($params['image']);
        If ($request->has('image')){

            Storage::delete($product->image);
            $path = $request->file('image')->store('products'); //в теге атрибут image
            $params['image'] = $path;
        }

//        foreach (['new', 'hit', 'recommend'] as $fielName){
//            if (isset($params[$fielName])){
//                $params[$fielName] = 1;
//            } else {
//                $params[$fielName] = 0;
//
//            }
//        }

        foreach (['new', 'hit', 'recommend'] as $fielName) {
            if (!isset($params[$fielName])) {

                $params[$fielName] = 0;
            }
        }

       $product->update($params);
        //$product->update($request->all());
        return redirect()->route('products.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');

    }
}
