<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::get();
        return view('auth.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.categories.form');
        //ggg
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request)
    public function store(CategoryRequest $request) //после созданию класса CategoryRequest
    {
//       $request->validate([
//        'code' =>'required', //    required - означает обязательно к заполнению
//            'name' => 'required',
//            'description' => 'required', //после созданию класса CategoryRequest,  переносим правила туда
//        ]);
        $params = $request->all();
         unset($params['image']);
        If ($request->has('image')){
            $path = $request->file('image')->store('categories'); //в теге атрибут image
            $params['image'] = $path;

        }

        //$path = $request->file('image')->store('categories'); //в теге атрибут image
        //$params = $request->all();
        //$params['image'] = $path;
        Category::create($params); //Category::create($request->all());
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Category $category)
    {
        return view('auth.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('auth.categories.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Category $category)
    public function update(CategoryRequest $request, Category $category)
    {
        $params = $request->all();
        unset($params['image']);
        If ($request->has('image')){

            Storage::delete($category->image);
            $path = $request->file('image')->store('categories'); //в теге атрибут image
            $params['image'] = $path;
        }
        $category->update($params); //$category->update($request->all());
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete($request->all());
        return redirect()->route('categories.index');
    }
}
