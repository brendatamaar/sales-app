<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // optional: batasi akses
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:view-user')->only('index','show');
        // $this->middleware('permission:create-user')->only('create','store');
        // $this->middleware('permission:edit-user')->only('edit','update');
        // $this->middleware('permission:delete-user')->only('destroy');
    }

    /**
     * List users
     */
    public function index(Request $request)
    {
        $q = $request->get('q');

        $users = User::with('store')
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('region', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(['q' => $q]);

        return view('users.index', compact('users', 'q'));
    }

    /**
     * Show form create
     */
    public function create()
    {
        $roles = Role::pluck('name', 'id');   // untuk select
        $stores = Store::orderBy('store_name')->get(['store_id', 'store_name']);
        return view('users.create', compact('roles', 'stores'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'region' => ['nullable', 'string', 'max:255'],
            'store_id' => ['nullable', 'integer', 'exists:stores,store_id'],
            'roles' => ['nullable', 'array'], // array of role names
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'region' => $data['region'] ?? null,
            'store_id' => $data['store_id'] ?? null,
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']); // role names
        }

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    /**
     * Show detail
     */
    public function show(User $user)
    {
        $user->load('store');
        return view('users.show', compact('user'));
    }

    /**
     * Edit form
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        $userRoles = $user->getRoleNames()->toArray();
        $stores = Store::orderBy('store_name')->get(['store_id', 'store_name']);
        return view('users.edit', compact('user', 'roles', 'userRoles', 'stores'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'region' => ['nullable', 'string', 'max:255'],
            'store_id' => ['nullable', 'integer', 'exists:stores,store_id'],
            'roles' => ['nullable', 'array'],
        ]);

        $user->username = $data['username'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->region = $data['region'] ?? null;
        $user->store_id = $data['store_id'] ?? null;
        $user->save();

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));
        $limit = (int) $request->get('limit', 20);

        $query = User::query()
            ->select(['id', 'username', 'email'])
            ->when($q, function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('username')
            ->limit($limit);

        $items = $query->get()->map(function ($row) {
            return [
                'id' => (string) $row->id,
                'text' => $row->username . ' (' . $row->email . ')',
            ];
        });

        return response()->json(['results' => $items]);
    }
}
