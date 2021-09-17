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
        //gate
        $request->validate(
            [
                'name'=>'string|max:100'
            ]);

        $role = Role::create($request->all());

        return back()->with('success', 'Role created');
    }


    public function destroy(Role $role){
        //gate
        $role->delete();
        return back()->with('success', 'Role deleted');
    }
}
