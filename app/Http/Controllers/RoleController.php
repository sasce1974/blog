<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RoleController extends Controller
{
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('admin');
    }


    /**
     * Save new user role
     *
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request){

        \Gate::authorize('admin-management');

        $request->validate(
            [
                'role_name'=>'string|max:100'
            ]);

        if(Role::create(['name'=> $request->role_name])){

            session()->flash('success', 'Role created');
        }else{

            session()->flash('error', 'Role not created');
        }

        return redirect('/dashboard#tabs-4');
    }


    /**
     * Delete user role
     *
     * @param Role $role
     * @return RedirectResponse|Redirector
     * @throws \Exception
     */
    public function destroy(Role $role){

        \Gate::authorize('admin-management');

        if($role->delete()){
            session()->flash('success', 'Role deleted');

            //todo handle users with this role
        }else{

            session()->flash('error', 'Role not deleted');
        }

        return redirect('/dashboard#tabs-4');

    }
}
