<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function listUsers(Request $request)
    {
        $results = User::query();
        if ($request->has('q')) {
            $results->orWhere('name', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('steamid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhere('guid', 'LIKE', '%'.$request->input('q').'%');
            $results->orWhereHas('names', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->input('q').'%');
            });
        }

        if ($request->has('individual-perms')) {
            if (1 == $request->input('individual-perms')) {
                $results->has('permissions', '>', 0);
            } else {
                $results->has('permissions', '=', 0);
            }
        }

        if ($request->has('has-groups')) {
            if (1 == $request->input('has-groups')) {
                $results->has('roles', '>', 0);
            } else {
                $results->has('roles', '=', 0);
            }
        }

        if ($request->has('group')) {
            $results->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->input('group'));
            });
        }

        $results = $results->with(['roles', 'permissions'])->paginate(20);
        $roles = Role::has('users')->get();

        return view('acl.users.list')
            ->with('results', $results)
            ->with('q', $request->input('q'))
            ->with('roles', $roles);
    }

    public function newUserPage()
    {
        $roles = Role::all();

        return view('acl.users.new')->with('roles', $roles);
    }

    public function newUser(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required|unique:users',
            'steamid' => 'required|unique:users',
            'email'   => 'required|email|unique:users',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->steamid = $request->steamid;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->roles);
        $user->save();

        return redirect(route('acl-users'))->with('status', 'Usuario añadido correctamente');
    }

    public function editUserPage($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('acl.users.edit')->with('user', $user)->with('permissions', $permissions)->with('roles', $roles);
    }

    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Si no es admin, comprobamos que vengan los datos pertinentes
//        if(!$user->isAdmin()) {
//            $this->validate($request, [
//                'name' => [
//                    'required',
//                    Rule::unique('users')->ignore($user->id),
//                ],
//                'steamid' => [
//                    'required',
//                    Rule::unique('users')->ignore($user->id),
//                ],
//                'email' => [
//                    'required',
//                    'email',
//                    Rule::unique('users')->ignore($user->id),
//                ]
//            ]);
//        }

//         Si no es admin guardar los datos cambiados
        if (! $user->isAdmin()) {
//            $user->name = $request->name;
//            $user->steamid = $request->steamid;
//            $user->email = $request->email;
            if (isset($request->disabled)) {
                $user->disabled = true;
            }
        }

        // Los permisos y grupos se guardan siempre
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);
        $user->save();

        return redirect(route('acl-users-edit', $user))->with('status', 'Usuario editado correctamente');
    }
}
