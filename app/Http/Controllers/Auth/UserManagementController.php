<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $roles = DB::table('roles')
            ->where('slug', '<>', 'super_admin')
            ->when(DB::table('users')->where('role', 'admin')->count() >= 4, function ($q) {
                $q->where('slug', '<>', 'admin');
            })
            ->when(DB::table('users')->where('role', 'manager')->count() >= 10, function ($q) {
                $q->where('slug', '<>', 'manager');
            })
            ->orderBy('name')
            ->get(['id', 'slug', 'name']);

        $permissions = DB::table('permissions')->orderBy('name')->get(['id', 'slug', 'name']);

        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'role'     => ['required', 'exists:roles,slug'],
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['nullable', 'email', 'required_without:phone', 'max:191', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20', 'required_without:email', 'unique:users,phone'],
            'password' => ['nullable', 'string', 'min:8'],
            'image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'education_level' => ['nullable', 'string', 'max:120'],
            'institute_name'  => ['nullable', 'string', 'max:191'],
            'gender'          => ['nullable', 'in:male,female,other'],
            'date_of_birth'   => ['nullable', 'date', 'before:today'],
        ];

        if (!in_array($request->input('role'), ['super_admin', 'examinee'])) {
            $rules['permissions']   = ['required', 'array', 'min:1'];
            $rules['permissions.*'] = ['integer', 'exists:permissions,id'];
        }

        $data = $request->validate($rules, [], [
            'permissions' => 'permissions',
        ]);

        $creator    = Auth::user();
        $targetRole = $data['role'];

        $allowed = match ($creator->role) {
            'super_admin' => true,
            'admin'       => in_array($targetRole, ['manager', 'employee', 'adv_user', 'examinee'], true),
            'Manager'     => in_array($targetRole, ['employee', 'adv_user', 'examinee'], true),
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
            'education_level' => $request->education_level,
            'institute_name'  => $request->institute_name,
            'gender'          => $request->gender,
            'date_of_birth'   => $request->date_of_birth,
        ]);

        if (!in_array($targetRole, ['super_admin', 'examinee'])) {
            $rows = collect($request->permissions)->map(fn($pid) => [
                'user_id'       => $user->id,
                'permission_id' => $pid,
            ]);

            if ($rows->isNotEmpty()) {
                DB::table('user_permission')->insert($rows->all());
            }
        }

        return redirect()->route('users.index')->with('status', 'User created.');
    }

    public function edit(User $user)
    {
        $permissions = DB::table('permissions')->orderBy('name')->get(['id', 'slug', 'name']);

        $user_permission_ids = DB::table('user_permission as up')
            ->join('permissions as p', 'up.permission_id', '=', 'p.id')
            ->where('up.user_id', $user->id)
            ->pluck('p.id')
            ->toArray();

        return view('users.edit', compact('user', 'user_permission_ids', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'role'            => ['required', 'exists:roles,slug'],
            'name'            => ['required', 'string', 'max:120'],
            'email'           => ['nullable', 'email', 'required_without:phone', 'max:191'],
            'phone'           => ['nullable', 'string', 'max:20', 'required_without:email'],
            'change_password' => ['nullable', 'boolean'],
            'password'        => ['nullable', 'string', 'min:8'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'education_level' => ['nullable', 'string', 'max:120'],
            'institute_name'  => ['nullable', 'string', 'max:191'],
            'gender'          => ['nullable', 'in:male,female,other'],
            'date_of_birth'   => ['nullable', 'date', 'before:today'],
        ];

        if (!in_array($request->input('role'), ['super_admin', 'examinee'])) {
            $rules['permissions']   = ['required', 'array', 'min:1'];
            $rules['permissions.*'] = ['integer', 'exists:permissions,id'];
        }

        $data = $request->validate($rules, [], [
            'permissions' => 'permissions',
        ]);

        $creator    = Auth::user();
        $targetRole = $data['role'];

        $allowed = match ($creator->role) {
            'super_admin' => true,
            'admin'       => in_array($targetRole, ['manager', 'employee', 'adv_user', 'examinee'], true),
            'Manager'     => in_array($targetRole, ['employee', 'adv_user', 'examinee'], true),
            'employee'    => $targetRole === 'examinee',
            default       => false,
        };

        abort_unless($allowed || $creator->id === $user->id, 403, 'Not allowed');

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'education_level' => $data['education_level'] ?? null,
            'institute_name'  => $data['institute_name'] ?? null,
            'gender'          => $data['gender'] ?? null,
            'date_of_birth'   => $data['date_of_birth'] ?? null,
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

        if (!in_array($targetRole, ['super_admin', 'examinee'])) {
            $rows = collect($data['permissions'])->map(fn($pid) => [
                'user_id'       => $user->id,
                'permission_id' => $pid,
            ]);

            if ($rows->isNotEmpty()) {
                DB::table('user_permission')->insert($rows->all());
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function details(Request $request)
    {
        $user = DB::table('users as u')
            ->join('roles as r', 'u.role', 'r.slug')
            ->select(
                'u.id',
                'u.name',
                'u.email',
                'u.phone',
                'r.name as role',
                'r.slug',
                'u.image',
                'u.education_level',
                'u.institute_name',
                'u.gender',
                'u.date_of_birth'
            )
            ->where('u.id', $request->id)
            ->first();

        if ($user) {
            $permissions = DB::table('permissions as p')
                ->join('user_permission as up', 'up.permission_id', '=', 'p.id')
                ->where('up.user_id', $request->id)
                ->orderBy('p.name')
                ->get(['p.id', 'p.slug', 'p.name']);
        }

        return view('users.details', compact('user', 'permissions'));
    }

    public function profile(Request $request, User $user)
    {
        return view('profile.edit', compact('user'));
    }

    public function profile_update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name'             => ['required', 'string', 'max:100'],
            'education_level'  => ['nullable', 'string', 'max:150'],
            'institute_name'   => ['nullable', 'string', 'max:150'],
            'gender'           => ['nullable', 'in:male,female,other'],
            'date_of_birth'    => ['nullable', 'date', 'before:today'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if (empty($user->email)) {
            $rules['email'] = ['nullable', 'email', 'max:150'];
        }

        if (empty($user->phone)) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        $validated = $request->validate($rules);

        if (!empty($user->email)) {
            unset($validated['email']);
        }
        if (!empty($user->phone)) {
            unset($validated['phone']);
        }

        if (!empty($validated['date_of_birth'])) {
            $validated['date_of_birth'] = Carbon::parse($validated['date_of_birth'])->format('Y-m-d');
        }

        if ($request->hasFile('image')) {
            if (!empty($user->image) && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $path = $request->file('image')->store('users', 'public');
            $validated['image'] = $path;
        }

        $user->fill($validated)->save();


        return redirect()->route('users.details', ['id' => $user->id])->with('success', 'Profile updated successfully.');
    }
}
