<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('admin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        \Gate::authorize('admin-management');

        $request->validate(['category_name' => 'required|string|max:50']);

        try{

            Category::create(['name'=>$request->category_name]);

            session()->flash('success', 'New category created');

        }catch (\Throwable $e){

            report($e);

            session()->flash('error', 'Category not created');
        }

        return redirect('/dashboard#tabs-3');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        try{
            $category->posts()->detach();

            $category->delete();

            session()->flash('success', 'Category ' . $category->name . ' deleted');

        }catch (\Throwable $e){

            report($e);

            session()->flash('error', 'Category ' . $category->name . ' not deleted');
        }

        return redirect('/dashboard#tabs-3');
    }
}
