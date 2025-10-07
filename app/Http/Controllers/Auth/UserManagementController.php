<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:list')->only('index');
        $this->middleware('permission:create')->only(['create', 'store']);
        $this->middleware('permission:update')->only(['edit', 'update']);
        $this->middleware('permission:delete')->only('destroy');
    }
    
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles       = DB::table('roles')->orderBy('name')->get(['id', 'slug', 'name']);
        $permissions = DB::table('permissions')->orderBy('name')->get(['id', 'slug', 'name']);

        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'role'     => ['required', 'exists:roles,slug'],
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['nullable', 'email', 'required_without:phone'],
            'phone'    => ['nullable', 'string', 'max:20', 'required_without:email'],
            'password' => ['nullable', 'string', 'min:8'],
            'image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($request->role !== 'super_admin') {
            $rules['permissions']   = ['required', 'array', 'min:1'];
            $rules['permissions.*'] = ['integer', 'exists:permissions,id'];
        }

        $request->validate($rules, [], [
            'permissions' => 'permissions',
        ]);

        $creator    = Auth::user();
        $targetRole = $request->role;

        $allowed = match ($creator->role) {
            'super_admin' => in_array($targetRole, ['super_admin', 'admin', 'examinee']),
            'admin'       => in_array($targetRole, ['employee', 'examinee']),
            'employee'    => $targetRole === 'examinee',
            default       => false,
        };

        abort_unless($allowed, 403, 'Not allowed');

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => $targetRole === 'examinee' ? null : ($request->password ? bcrypt($request->password) : bcrypt('password')),
            'role'     => $targetRole,
            'image'    => $imagePath,
        ]);

        if ($targetRole !== 'super_admin') {
            $rows = collect($request->permissions)->map(fn($pid) => [
                'user_id'       => $user->id,
                'permission_id' => $pid,
            ]);
        }

        DB::table('user_permission')->insert($rows->all());

        return redirect()->route('users.index')->with('status', 'User created.');
    }

    public function edit(User $user)
    {
        $roles       = DB::table('roles')->orderBy('name')->get(['id', 'slug', 'name']);
        $permissions = DB::table('permissions')->orderBy('name')->get(['id', 'slug', 'name']);

        $user_permission_ids = DB::table('user_permission as up')
            ->join('permissions as p', 'up.permission_id', '=', 'p.id')
            ->where('up.user_id', $user->id)
            ->pluck('p.id')
            ->toArray();

        return view('users.edit', compact('user', 'user_permission_ids', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'role'            => 'required|in:super_admin,admin,employee,examinee',
            'name'            => 'required|string|max:120',
            'email'           => 'nullable|email|required_without:phone',
            'phone'           => 'nullable|string|max:20|required_without:email',
            'change_password' => 'nullable|boolean',
            'password'        => 'nullable|string|min:8',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($request->input('role') !== 'super_admin') {
            $rules['permissions']   = ['required', 'array', 'min:1'];
            $rules['permissions.*'] = ['integer', 'exists:permissions,id'];
        }

        $data = $request->validate($rules, [], [
            'permissions' => 'permissions',
        ]);

        $creator    = Auth::user();
        $targetRole = $data['role'];
        $allowed    = match ($creator->role) {
            'super_admin' => in_array($targetRole, ['super_admin', 'admin', 'examinee']),
            'admin'       => in_array($targetRole, ['employee', 'examinee']),
            'employee'    => $targetRole === 'examinee',
            default       => false,
        };

        abort_unless($allowed || $creator->id === $user->id, 403, 'Not allowed');

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];

        if (! empty($data['password']) && $creator->id === $user->id && $request->boolean('change_password')) {
            $updateData['password'] = bcrypt($data['password']);
        }

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('users', 'public');
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $updateData['image'] = $newPath;
        }

        $user->update($updateData);

        DB::table('user_permission')->where('user_id', $user->id)->delete();

        if ($targetRole !== 'super_admin') {
            $rows = collect($data['permissions'])->map(fn($pid) => [
                'user_id'       => $user->id,
                'permission_id' => $pid,
            ]);
        }

        DB::table('user_permission')->insert($rows->all());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
