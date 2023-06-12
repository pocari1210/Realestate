<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{

  public function AllPermission()
  {
    $permissions = Permission::all();

    return view(
      'backend.pages.permission.all_permission',
      compact('permissions')
    );
  } // End Method 

  public function AddPermission()
  {
    return view('backend.pages.permission.add_permission');
  } // End Method 

  public function StorePermission(Request $request)
  {
    $permission = Permission::create([
      'name' => $request->name,
      'guard_name' => $request->guard_name,
    ]);

    $notification = array(
      'message' => 'Permission Create Successfully',
      'alert-type' => 'success'
    );

    return redirect()->route('all.permission')->with($notification);
  } // End Method 

  public function EditPermission($id)
  {
    $permission = Permission::findOrFail($id);

    return view(
      'backend.pages.permission.edit_permission',
      compact('permission')
    );
  } // End Method 

  public function UpdatePermission(Request $request)
  {
    $per_id = $request->id;

    Permission::findOrFail($per_id)->update([
      'name' => $request->name,
      'guard_name' => $request->guard_name,
    ]);

    $notification = array(
      'message' => 'Permission Updated Successfully',
      'alert-type' => 'success'
    );

    return redirect()->route('all.permission')->with($notification);
  } // End Method 


  public function DeletePermission($id)
  {
    Permission::findOrFail($id)->delete();

    $notification = array(
      'message' => 'Permission Deleted Successfully',
      'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  } // End Method 

  public function Export()
  {

    return Excel::download(new PermissionExport, 'permission.xlsx');
  } // End Method 

  public function ImportPermission()
  {
    return view('backend.pages.permission.import_permission');
  } // End Method 

  /////////// Role ALL Method ///////////////

  public function AllRoles()
  {
    $roles = Role::all();

    return view(
      'backend.pages.roles.all_roles',
      compact('roles')
    );
  } // End Method 

  public function AddRoles()
  {
    return view('backend.pages.roles.add_roles');
  } // End Method 

  public function StoreRoles(Request $request)
  {
    Role::create([
      'name' => $request->name,
    ]);

    $notification = array(
      'message' => 'Role Create Successfully',
      'alert-type' => 'success'
    );

    return redirect()->route('all.roles')->with($notification);
  } // End Method 

  public function EditRoles($id)
  {

    $roles = Role::findOrFail($id);
    return view('backend.pages.roles.edit_roles', compact('roles'));
  } // End Method 


  public function UpdateRoles(Request $request)
  {
    $role_id = $request->id;

    Role::findOrFail($role_id)->update([
      'name' => $request->name,
    ]);

    $notification = array(
      'message' => 'Role Updated Successfully',
      'alert-type' => 'success'
    );

    return redirect()->route('all.roles')->with($notification);
  } // End Method 

  public function DeleteRoles($id)
  {

    Role::findOrFail($id)->delete();

    $notification = array(
      'message' => 'Role Deleted Successfully',
      'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  } // End Method 

}
