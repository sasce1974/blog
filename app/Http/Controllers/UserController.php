<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        \Gate::authorize('admin-management');

        $users = User::paginate(10);

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        \Gate::authorize('admin-management');
        return "User creation form";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Gate::authorize('admin-management');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['numeric', 'nullable']
        ]);

        //return back to index instead...
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at'=> now(),
            'role_id' => $request->role_id
        ]);

        return redirect()->back()->with('success', 'User created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        \Gate::authorize('view-profile', $user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        \Gate::authorize('manage-profile', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        \Gate::authorize('manage-profile', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', "unique:users,email,$user->id"],
            'role_id' => ['nullable', 'numeric'],
        ]);

        $user->name = $request->name;
        if($request->filled('email') && $request->email !== $user->email){
            $user->email = $request->email;
        }
        if($request->filled('role_id') && Auth::user()->isAdmin()){
            $user->role_id = $request->role_id;
        }

        $user->save();

        if($request->has('image')){

            $request->validate([
                'image'=>'image|max:2048|mimes:jpeg,bmp,png,jpg'
            ]);

            $path = Storage::disk('public')->putFile('user_photo', $request->file('image'));

            $image = new Photo(['path'=>$path, 'alt'=>$request->alt]);

            $user->photo()->save($image);
        }

        //return to the user show view
        return view('user.show', compact('user'));
    }

    public function storeImage(User $user, Request $request){
        $request->validate([
            'image'=>'image|max:2048|mimes:jpeg,bmp,png,jpg'
        ]);

        $path = Storage::disk('public')->putFile('user_photo', $request->file('image'));

        $image = new Photo(['path'=>$path, 'alt'=>$request->alt]);

        $user->photo()->save($image);

        return back()->with('success', 'Photo uploaded');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        \Gate::authorize('manage-profile', $user);

        foreach ($user->posts() as $post){
            //$post->comments()->delete();
            $post->categories()->sync([]);
            $post->delete();
        }

        $user->delete();

        //if user is not the admin, logout...
        if(Auth::check() && !Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('post')->with('success', 'User deleted');
        }

        return redirect()->back()->with('success', 'User deleted');

        //return redirect()->route('post')->with('success', 'User deleted');
    }
}
