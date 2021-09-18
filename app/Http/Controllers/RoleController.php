<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(){
        return Role::all();
    }

    public function show(Role $role){
        return $role;
    }

    public function create(){
        //admin gate
        return view('role.create');
    }

    public function store(Request $request){

        \Gate::authorize('admin-management');

        $request->validate(
            [
                'role_name'=>'string|max:100'
            ]);

        Role::create(['name'=> $request->role_name]);

        session()->flash('success', 'Role created');

        return redirect('/dashboard#tabs-4');
    }


    public function destroy(Role $role){

        \Gate::authorize('admin-management');

        $role->delete();

        session()->flash('success', 'Role deleted');

        return redirect('/dashboard#tabs-4');

    }
}
