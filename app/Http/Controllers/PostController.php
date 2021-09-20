<?php

namespace App\Http\Controllers;

use App\Category;
use App\Photo;
use App\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')
            ->except(['index', 'indexByCategory', 'search', 'show']);

        $this->middleware('admin')
            ->only(['approve', 'disapprove']);
    }


    /**
     * Display a listing of the resource.
     * Main page, open to view by all
     *
     * @return Factory|View
     */
    public function index()
    {
        $posts = Post::where('approved', true)->orderByDesc('updated_at')->paginate(6);

        $categories = Category::all();

        $featured = null;

        //get one random post
        if($posts->count() > 0)
            $featured = $posts->random(1)->first();

        return view('blog', compact('posts', 'featured', 'categories'));
    }


    /**
     * Get posts by specific category
     *
     * @param $id
     * @return Factory|View
     */
    public function indexByCategory($id){

        $category = Category::findOrFail($id);

        $posts = $category->posts()->orderByDesc('updated_at')->paginate(9);

        $categories = Category::all();

        return view('blog', compact('posts', 'categories'));
    }


    /**
     * List posts according given search value, search in posts and comments
     *
     * @param Request $request
     * @return Factory|View
     */
    public function search(Request $request){

        $posts = Post::with('comments')

            ->where('title', 'like', '%' . $request->search . '%')

            ->orWhere('content', 'like', '%' . $request->search . '%')

            ->orWhere(function ($query) use ($request){

                $query->whereHas('comments', function ($q) use ($request){
                    $q->where('comment', 'like', '%' . $request->search . '%');
                });

            })

            ->orderByDesc('updated_at')

            ->paginate(9);

        $categories = Category::all();

        return view('blog', compact('posts', 'categories'))
            ->with('success', 'Found ' . $posts->count() . ' posts');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {

        return view('post.create', ['categories' => Category::all()]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'title'=>'required|string|max:255',
            'content'=> 'required|string|max:2000|min:50',
            'category_id'=>'array|nullable',
            'image'=>'image|max:2048|mimes:jpeg,bmp,png,jpg'
        ]);

        $slug = Str::of($request->title)->slug('_');

        try {

            $post = Auth::user()->posts()->create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'slug' => $slug
            ]);

        }catch (\Throwable $e){

            report($e);

            return redirect()->route('post.create')
                ->withInput()
                ->with('error', 'Post not created');
        }

        $post->categories()->sync($request->category_id);

        if($request->has('image')){

            if(!$this->storeImage($post, $request)){

                session()->flash('error', 'Image not saved');
            }
        }

        return redirect()->route('post.show', $post->slug)
            ->with('success', 'Post created');
    }


    /**
     * Display the specified resource.
     *
     * @param Post $post
     * @return Factory|View
     */
    public function show(Post $post)
    {

        $categories = Category::all();

        $postCategories = $post->categories;

        //todo set cookie to prevent continuous incrementing
        $post->increment('viewed');

        $postsFromSameAuthor = Post::where('user_id', $post->author->id)
            ->where('id', '<>', $post->id)
            ->orderByDesc('created_at')->get();

        return view('post.show',
            compact('post','postCategories','categories', 'postsFromSameAuthor'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Factory|View
     */
    public function edit(Post $post)
    {
        \Gate::authorize('edit-post', $post);

        $categories = Category::all();

        $postCategoriesArray = $post->categories->pluck('id')->toArray();

        return view('post.edit',
            compact('post', 'categories', 'postCategoriesArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
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

        try{

        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'slug' => $slug
        ]);

        }catch (\Throwable $e){

            report($e);

            return redirect()->route('post.edit', $post->slug)
                ->withInput()
                ->with('error', 'Post not updated');
        }

        $post->categories()->sync($request->category_id);

        if($request->has('image')){

            if(!$this->storeImage($post, $request)){

                session()->flash('error', 'Image not uploaded');
            }

        }

        return redirect()->route('post.show', $post->slug)
            ->with('success', 'Post updated');
    }


    /**
     * Handles saving image on disk and database
     *
     * @param Post $post
     * @param Request $request
     * @return bool
     */
    private function storeImage(Post $post, Request $request){

        \Gate::authorize('edit-post', $post);

        try {

            $extension = $request->file('image')->extension();

            $path = Storage::disk('public')
                ->putFileAs('post_photo', $request->file('image'),
                    $post->id . "." . $extension);

            if (!$path) return false;

            $image = new Photo(['path' => $path, 'alt' => $request->alt]);

            $post->photo()->save($image);

            return true;

        }catch (\Throwable $e){

            report($e);

            return false;
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        \Gate::authorize('delete-post', $post);

        $post->categories()->detach();

        if(!$this->deletePhotoFromDisk($post)){

            session()->flash('error', 'Post image has not been removed');
        }

        try {

            foreach ($post->allComments as $comment){

                $comment->delete();
            }

            $post->delete();

        }catch (\Throwable $e){

            report($e);

            return redirect()->route('post')->with('error', 'Post was not deleted');
        }

        return redirect()->route('post')->with('success', 'Post deleted');
    }


    /**
     * Delete post image from disk and database
     *
     * @param Post $post
     * @return bool
     */
    private function deletePhotoFromDisk(Post $post){

        if(!$post->photo || !Storage::disk('public')->exists($post->photo->path)){
            return false;
        }

        try{

            if (!Storage::disk('public')->delete($post->photo->path)) {
                return false;
            }

            $post->photo->delete();

        }catch (\Throwable $e){

            report($e);

            return false;
        }

        return true;
    }


    /**
     * Delete only image from post
     *
     * @param Post $post
     * @return RedirectResponse
     */
    public function deletePhoto(Post $post){

        \Gate::authorize('edit-post', $post);

        if(!$this->deletePhotoFromDisk($post)){

            return back()->with('error', 'Photo not deleted');
        }

        return back()->with('success', 'Photo deleted');
    }


    /**
     * Approve post
     *
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function approve($id){

        \Gate::authorize('admin-management');

        $post = Post::findOrFail($id);

        $post->update(['approved'=>true]);

        session()->flash('success', 'Post approved');

        return redirect('/dashboard#tabs-2');
    }

    /**
     * Disapprrove post
     *
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function disapprove($id){

        \Gate::authorize('admin-management');

        $post = Post::findOrFail($id);

        $post->update(['approved'=>false]);

        session()->flash('success', 'Post disapproved');

        return redirect('/dashboard#tabs-2');
    }
}
