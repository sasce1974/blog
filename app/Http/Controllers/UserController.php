<?php

namespace App\Http\Controllers;

use App\Photo;
use Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        Gate::authorize('admin-management');

        $users = User::paginate(10);

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        Gate::authorize('admin-management');
        return "User creation form";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        Gate::authorize('admin-management');

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
     * @return Response
     */
    public function show(User $user)
    {
        Gate::authorize('view-profile', $user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user)
    {

        \Gate::authorize('manage-profile', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Factory|View
     */
    public function update(Request $request, User $user)
    {

        Gate::authorize('manage-profile', $user);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', "unique:users,email,$user->id"],
            'role_id' => ['nullable', 'numeric'],
        ]);

        $user->name = $request->name;
        if($request->filled('email') && $request->email !== $user->email){

            $user->email = $request->email;
        }

        if($request->filled('role_id') && $request->user()->isAdmin()){

            $user->role_id = $request->role_id;
        }

        try {

            $user->save();

            session()->flash('success', 'Profile updated');

        }catch (\Throwable $e){

            report($e);

            session()->flash('error', 'Profile not updated');
        }

        if($request->has('image')){

            if($this->storeImage($user, $request)){

                session()->flash('success', 'Photo uploaded');
            }else{

                session()->flash('error', 'Image not saved');
            }

        }

        return view('user.show', compact('user'));
    }

    public function uploadPhoto(User $user, Request $request){

        \Gate::authorize('manage-profile', $user);

        if($this->storeImage($user, $request)){
            session()->flash('success', 'Photo uploaded');
        }else{
            session()->flash('error', 'Image not saved');
        }

        return back();
    }


    private function storeImage(User $user, Request $request){

        \Gate::authorize('manage-profile', $user);

        $request->validate([
            'image' => 'required|image|max:2048|mimes:jpeg,bmp,png,jpg',
            'alt' => 'nullable|string|max:50'
        ]);
        try {
            $extension = $request->file('image')->extension();

            $path = Storage::disk('public')
                ->putFileAs('user_photo', $request->file('image'),
                    $user->id . "." . $extension);

            if(!$path) throw new \Exception("Image not uploaded on disk");

            $image = new Photo(['path' => $path, 'alt' => $request->alt]);

            $user->photo()->save($image);

            return true;

        }catch (\Throwable $e){

            report($e);

            return false;
        }
    }


    public function deletePhoto(User $user){

        \Gate::authorize('manage-profile', $user);

        Storage::disk('public')->delete($user->photo->path);

        $user->photo->delete();

        return back()->with('success', 'Photo deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(User $user)
    {
        Gate::authorize('manage-profile', $user);

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
