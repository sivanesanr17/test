<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::select('id', 'name')
            ->with(['permissions:id,name'])
            ->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::select('id', 'name')->get();

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $role = Role::create(['name' => $validated['name']]);

            $role->syncPermissions($this->normalizePermissionIds($validated['permissions'] ?? []));

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating role: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->validated(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create role. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions:id,name');

        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::select('id', 'name')->get();
        $role->load('permissions:id,name');

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $role->update(['name' => $validated['name']]);

            $role->syncPermissions($this->normalizePermissionIds($validated['permissions'] ?? []));

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating role: '.$e->getMessage(), [
                'exception' => $e,
                'role_id' => $role->id,
                'request_data' => $request->validated(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update role. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();

            $role->delete();

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting role: '.$e->getMessage(), [
                'exception' => $e,
                'role_id' => $role->id,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete role. Please try again.');
        }
    }

    /**
     * Normalize permission IDs from form input (strings) to integers.
     *
     * @param array $permissions
     * @return array
     */
    private function normalizePermissionIds(array $permissions): array
    {
        return array_values(array_filter(array_map('intval', $permissions)));
    }
}
