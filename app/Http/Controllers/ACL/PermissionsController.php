<?php

namespace App\Http\Controllers\ACL;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;

class PermissionsController extends Controller
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

    public function listPermissions()
    {
        $permissions = Permission::all();
        return view('acl.permissions.list')->with('permissions', $permissions);
    }
}
