<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Role;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

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

        $this->middleware('admin');
    }


    /**
     * Show the application dashboard.
     *
     * @return Factory|View
     */
    public function index()
    {

        $users = User::with('role')->get();

        $posts = Post::with('allComments', 'author')
            ->orderByDesc('id')->get(); //->paginate(5)->fragment('tabs-2');

        $categories = Category::all();

        $roles = Role::all();

        return view('home', compact('users', 'posts', 'categories', 'roles'));
    }
}
