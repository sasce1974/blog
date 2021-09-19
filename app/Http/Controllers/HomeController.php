<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Role;
use App\User;
use Couchbase\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(Auth::user()->isAdmin()){

            $users = User::paginate(15);

            $posts = Post::with('allComments')->paginate(15);

            $categories = Category::all();

            $roles = Role::all();
            //dd($posts[3]->allComments);
            return view('home', compact('users', 'posts', 'categories', 'roles'));
        }

        return redirect('/');
    }
}
