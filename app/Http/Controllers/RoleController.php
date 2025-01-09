<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function array_combine_safe($keys, $values): array
    // {
    //     $count_keys = count($keys);
    //     $count_values = count($values);

    //     $combined = array();

    //     if ($count_keys > $count_values) {
    //         // If there are more keys than values, fill extra keys with null
    //         for ($i = 0; $i < $count_keys; $i++) {
    //             $combined[$keys[$i]] = isset($values[$i]) ? $values[$i] : null;
    //         }
    //     } elseif ($count_values > $count_keys) {
    //         // If there are more values than keys, discard extra values
    //         for ($i = 0; $i < $count_values; $i++) {
    //             $combined[$keys[$i]] = isset($keys[$i]) ? $values[$i] : null;
    //         }
    //     } else {
    //         // Arrays have the same length, use standard array_combine
    //         $combined = array_combine($keys, $values);
    //     }

    //     return $combined;
    // }

    public function index()
    {
        $roles = Role::all();
        return view("role.index")->with('roles', $roles);
    }

    public function save()
    {
        return view("role.save");
    }
    public function edit($id)
    {
        $permission = new Permission();
        // get routes from permissions table
        $routesForPermissions = $permission->where('role_id', $id)->pluck('routes')->toArray();
        // get role from the roles table
        $roles = Role::find($id);

        return view('role.edit', compact(['routesForPermissions', 'roles', 'id']));
    }
    public function store(Request $request)
    {
        $routes = $request->all();

        // Create a new role
        $roles = Role::create([
            'role_name' => $request->input('name'),
        ]);

        //  Get the newly created role id
        $roleId = $roles->id;

        // Get all the keys from the permissions array
        $permissions = array_keys(permissions_array());

        //  Combine permissions and routes arrays to a separate associative array
        $permissionsAndRoutes = array_combine($permissions, $routes['routes']);

        //  Assign permissions to the role
        foreach ($permissionsAndRoutes as $key => $value) {
            Permission::create([
                'role_id' => $roleId,
                'permission' => $key,
                'routes' => $value
            ]);
        }

        return redirect('role');
    }
    public function update(Request $request, $id)
    {
        $routes = $request->all();

        // $roleHasPermissions = Permission::where('role_id', $id)->pluck('routes')->toArray();

        // $chkPermissionArr = [];

        $roles = Role::find($id);

        if ($roles) {
            $roles->role_name = $request->input('name');
            $roles->save();
        }

        $roleId = $id;

        $permissions = array_keys(permissions_array());

        //  Combine permissions and routes arrays to a separate associative array
        $permissionsAndRoutes = array_combine($permissions, $routes['routes']);

        //  Assign permissions to the role
        foreach ($permissionsAndRoutes as $key => $value) {
            Permission::where('role_id', $roleId)->update([
                'permission' => $key,
                'routes' => $value
            ]);
        }

        // foreach ($routes as $key => $data) {
        //     if (is_array($data)) {
        //         foreach ($data as $value) {
        //             array_push($chkPermissionArr, $value);
        //             if (!in_array($value, $roleHasPermissions)) {
        //                 Permission::create([
        //                     'role_id' => $roleId,
        //                     'permission' => $key,
        //                     'routes' => $value
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // for ($i = 0; $i < count($roleHasPermissions); $i++) {
        //     if (!in_array($roleHasPermissions[$i], $chkPermissionArr)) {
        //         $permissionToDelete = Permission::where('routes', $roleHasPermissions[$i])->where('role_id', $roleId);
        //         if ($permissionToDelete) {
        //             $permissionToDelete->delete();
        //         }
        //     }
        // }

        return redirect('role');
    }

    public function delete($id)
    {


        $id = json_decode($id);

        if (is_array($id)) {

            for ($i = 0; $i < count($id); $i++) {
                $roles = Role::find($id[$i]);
                $roles->delete();

                $permissions = Permission::where('role_id', $id[$i]);
                if ($permissions) {
                    $permissions->delete();
                }

                $user = User::where('role_id', $id[$i]);
                if ($user) {
                    $user->delete();
                }
            }
        } else {
            $roles = Role::find($id);
            $roles->delete();

            $permissions = Permission::where('role_id', $id);
            if ($permissions) {
                $permissions->delete();
            }

            $user = User::where('role_id', $id);
            if ($user) {
                $user->delete();
            }
        }

        return back();
    }
}
