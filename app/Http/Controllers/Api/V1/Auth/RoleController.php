<?php

namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller
{
    // ✅ Get all roles with permissions
    public function index()
    {
        //     $roles = Role::with('permissions')->get();
        $roles =   QueryBuilder::for(Role::class)
            ->allowedFilters('name')
            ->with('permissions')
            ->allowedIncludes('permissions')
            ->get();
        return response()->json($roles);
    }

    // ✅ Get all permissions (for dropdown or multi-select)
    public function permissions()
    {
        $permissions = Permission::all(['id', 'name']);
        $permissions = $permissions->groupBy(function ($item) {
            // Extract the entity name (after first "_")
            return Str::after($item['name'], '_');
        });

        return response()->json($permissions);
    }

    // ✅ Create a new role with multiple permissions
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create($validated);

        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'message' => 'Role created successfully.',
            'data' => $role->load('permissions'),
        ], 201);
    }


    // ✅ Show a single role with permissions
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    // ✅ Update a role and its permissions
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('roles')->ignore($role->id)],
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'message' => 'Role updated successfully.',
            'data' => $role->load('permissions'),
        ]);
    }

    // ✅ Delete a role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}
