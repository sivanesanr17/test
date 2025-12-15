<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = User::with('roles:id,name')->latest();

        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($request->expectsJson()) {
            return response()->json([
                'users' => $query->get()->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'roles' => $user->roles->map(fn($r) => ['name' => $r->name]),
                        'created_at' => $user->created_at->format('Y-m-d H:i'),
                    ];
                })
            ]);
        }

        $users = $query->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Display specific user.
     */
    public function show(User $user): View
    {
        $user->load([
            'roles:id,name',
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Show user creation form.
     */
    public function create(): View
    {
        $roles = Role::select('id', 'name')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Store new user.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $validated['password'] = Hash::make($validated['password']);

            $roleId = $validated['role'] ?? null;
            unset($validated['role']);

            $user = User::create($validated);

            if ($roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->assignRole($role);
                }
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating user: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Show the user edit form.
     */
    public function edit(User $user): View
    {
        $roles = Role::select('id', 'name')->get();
        $user->load('roles:id,name');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the user.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $roleId = $validated['role'] ?? null;
            unset($validated['role']);

            $user->update($validated);

            if ($roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    $user->syncRoles([$role]);
                }
            } else {
                $user->syncRoles([]);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating user: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id,
                'request_data' => $request->except(['password', 'password_confirmation']),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Delete user.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting user: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $user->id,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }
}
