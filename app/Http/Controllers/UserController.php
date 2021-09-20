<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('admin')->only(['store', 'destroy']);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
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

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'role_id' => $request->role_id
            ]);
        }catch (\Throwable $e){

            report($e);

            return redirect()->back()->with('error', 'User not created');
        }

        return redirect()->back()->with('success', 'User created');
    }


    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function show(User $user)
    {
        \Gate::authorize('view-profile', $user);

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

    /**
     * Upload/Update only photo from show profile view
     *
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     */
    public function uploadPhoto(User $user, Request $request){

        \Gate::authorize('manage-profile', $user);

        if($this->storeImage($user, $request)){
            session()->flash('success', 'Photo uploaded');
        }else{
            session()->flash('error', 'Image not saved');
        }

        return back();
    }


    /**
     * Handles saving image on disk and database
     *
     * @param User $user
     * @param Request $request
     * @return bool
     */
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

            //Todo image file name can be random string and later to handle replace image
            // when uploading new one...

            if(!$path) throw new \Exception("Image not uploaded");

            $image = new Photo(['path' => $path, 'alt' => $request->alt]);

            $user->photo()->save($image);

            return true;

        }catch (\Throwable $e){

            report($e);

            return false;
        }
    }


    /**
     * Delete user image
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function deletePhoto(User $user){

        \Gate::authorize('manage-profile', $user);

        try {

            Storage::disk('public')->delete($user->photo->path);

            $user->photo->delete();

        }catch (\Throwable $e){

            report($e);

            return back()->with('error', 'Photo not deleted');
        }

        return back()->with('success', 'Photo deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        \Gate::authorize('manage-profile', $user);

        //Delete all user posts and comments, detach posts from categories
        // then delete the user
        try {

            foreach ($user->posts as $post) {

                $post->categories()->detach();

                $post->delete();
            }

            foreach ($user->comments as $comment) {

                $comment->delete();
            }

            $user->delete();

        }catch (\Throwable $e){

            report($e);

            return redirect()->back()->with('error', 'User not deleted');

        }

        //if user is not the admin, logout...
        if(Auth::check() && !Auth::user()->isAdmin()) {
            Auth::logout();

            return redirect()->route('post')->with('success', 'User deleted');
        }

        return redirect()->back()->with('success', 'User deleted');

    }
}
