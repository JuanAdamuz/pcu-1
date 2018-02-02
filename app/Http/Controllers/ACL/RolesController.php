<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function listRoles()
    {
        $roles = Role::all();
        return view('acl.roles.list')->with('roles', $roles);
    }

    public function newRolePage()
    {
        $permissions = Permission::all();
        return view('acl.roles.new')->with('permissions', $permissions);
    }

    public function newRole(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles',
            'display_name' => 'required|unique:roles',
            'description' => 'required|min:5|max:140',
        ], [
            'description.min' => 'La descripción debe tener como mínimo :min caracteres.',
            'description.max' => 'La descripción debe tener como máximo :min caracteres.',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $role->syncPermissions($request->permissions);
        $role->save();

        return redirect(route('acl-roles'))->with('status', 'Grupo creado correctamente');
    }

    public function editRolePage($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('acl.roles.edit')->with('role', $role)->with('permissions', $permissions);
    }

    public function editRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($role->id),
            ],
            'display_name' => [
                'required',
                Rule::unique('roles')->ignore($role->id),
            ],
            'description' => 'required|min:5|max:140',
        ], [
            'description.min' => 'La descripción debe tener como mínimo :min caracteres.',
            'description.max' => 'La descripción debe tener como máximo :min caracteres.',
        ]);

        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        $role->syncPermissions($request->permissions);
        $role->save();

        return redirect(route('acl-roles-edit', $role))->with('status', 'Grupo editado correctamente');
    }

    public function deleteRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->delete();
        return redirect(route('acl-roles', $role))->with('status', 'Grupo eliminado correctamente');
    }
}
