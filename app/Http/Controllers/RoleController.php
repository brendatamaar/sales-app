<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-permissions');
    }

    /**
     * Display role hierarchy and permissions
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $roleHierarchy = $this->getRoleHierarchy();

        return view('roles.index', compact('roles', 'roleHierarchy'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if current user can assign this role
        if (!$this->canAssignRole($request->role)) {
            return back()->with('error', 'You do not have permission to assign this role.');
        }

        // Remove all existing roles and assign new one
        $user->syncRoles([$request->role]);

        // Update the role field in users table for consistency
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        $users = User::role($role)->with('store')->get();
        return response()->json($users);
    }

    /**
     * Check role hierarchy and permissions
     */
    public function checkPermissions(User $user)
    {
        return [
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'can_manage_users' => $user->can('create-users'),
            'can_view_reports' => $user->can('view-reports'),
        ];
    }

    /**
     * Get role hierarchy for display
     */
    private function getRoleHierarchy()
    {
        return [
            'super admin' => [
                'level' => 5,
                'description' => 'Full system access and administration',
                'manages' => ['All users and system settings']
            ],
            'regional manager' => [
                'level' => 4,
                'description' => 'Manages multiple stores in a region',
                'manages' => ['All stores in region, managers, leaders, staff']
            ],
            'manager' => [
                'level' => 3,
                'description' => 'Manages store operations and staff',
                'manages' => ['Store operations, leaders, staff']
            ],
            'leaders' => [
                'level' => 2,
                'description' => 'Team leader with supervisory responsibilities',
                'manages' => ['Staff members, daily operations']
            ],
            'staff' => [
                'level' => 1,
                'description' => 'Basic operational role',
                'manages' => ['Own tasks and customer interactions']
            ]
        ];
    }

    /**
     * Check if current user can assign specific role
     */
    private function canAssignRole($targetRole)
    {
        $user = auth()->user();

        // Super admin can assign any role
        if ($user->hasRole('super admin')) {
            return true;
        }

        // Regional manager can assign manager, leaders, staff
        if ($user->hasRole('regional manager')) {
            return in_array($targetRole, ['manager', 'leaders', 'staff']);
        }

        // Manager can assign leaders, staff
        if ($user->hasRole('manager')) {
            return in_array($targetRole, ['leaders', 'staff']);
        }

        // Leaders can assign staff
        if ($user->hasRole('leaders')) {
            return $targetRole === 'staff';
        }

        return false;
    }

    /**
     * Get available roles for current user to assign
     */
    public function getAssignableRoles()
    {
        $user = auth()->user();
        $allRoles = ['staff', 'leaders', 'manager', 'regional manager', 'super admin'];

        if ($user->hasRole('super admin')) {
            return $allRoles;
        } elseif ($user->hasRole('regional manager')) {
            return ['staff', 'leaders', 'manager'];
        } elseif ($user->hasRole('manager')) {
            return ['staff', 'leaders'];
        } elseif ($user->hasRole('leaders')) {
            return ['staff'];
        }

        return [];
    }

    /**
     * Middleware helper - check if user can manage users at specific level
     */
    public static function canManageUsersInStore($storeId)
    {
        $user = auth()->user();

        // Super admin and regional manager can manage any store
        if ($user->hasAnyRole(['super admin', 'regional manager'])) {
            return true;
        }

        // Manager and leaders can only manage users in their own store
        if ($user->hasAnyRole(['manager', 'leaders'])) {
            return $user->store_id == $storeId;
        }

        return false;
    }
}