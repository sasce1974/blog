<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $posts = Post::where('approved', true)->get();

        $categories = Category::all();

        $featured = null;

        if($posts->count() > 0)
            $featured = $posts->random(1)->first();

        return view('blog', compact('posts', 'featured', 'categories'));
    }


    public function indexByCategory($id){

        $category = Category::findOrFail($id);

        $posts = $category->posts;

        $categories = Category::all();

        return view('blog', compact('posts', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view('post.create', ['categories' => Category::all()]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|string|max:255',
            'content'=> 'required|string|max:2000|min:50',
            'category_id'=>'array|nullable'
        ]);

        $slug = Str::of($request->title)->slug('_');
//dd($slug);
        $post = Auth::user()->posts()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => $slug
        ]);

        $post->categories()->sync($request->category_id);

        return redirect()->route('post.show', $post->slug)->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $categories = Category::all();

        $postCategories = $post->categories;

        //todo set cookie to prevent continuous incrementing
        $post->increment('viewed');

        return view('post.show', compact('post','postCategories','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        \Gate::authorize('edit-post', $post);

        $categories = Category::all();

        $postCategoriesArray = $post->categories->pluck('id')->toArray();

        return view('post.edit', compact('post', 'categories', 'postCategoriesArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        \Gate::authorize('edit-post', $post);

        $request->validate([
            'title'=>'required|string|max:255',
            'content'=> 'required|string|max:2000|min:50',
            'category_id'=>'array|nullable'
        ]);

        $slug = Str::of($request->title)->slug('_');

        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => $slug
        ]);

        $post->categories()->sync($request->category_id);

        return redirect()->route('post.show', $post->slug)->with('success', 'Post updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        \Gate::authorize('delete-post', $post);

        //$post->comments()->delete();

        $post->delete();

        return redirect()->route('post')->with('success', 'Post deleted');
    }


    public function approve($id){
        $post = Post::findOrFail($id);

        $post->update(['approved'=>true]);

        session()->flash('success', 'Post approved');

        return redirect('/dashboard#tabs-2');
    }

    public function disapprove($id){
        $post = Post::findOrFail($id);

        $post->update(['approved'=>false]);

        session()->flash('success', 'Post disapproved');

        return redirect('/dashboard#tabs-2');
    }
}
