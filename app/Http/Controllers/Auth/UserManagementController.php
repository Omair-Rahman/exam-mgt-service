<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role'     => 'required|in:super_admin,admin,employee,examinee',
            'name'     => 'required|string|max:120',
            'email'    => 'nullable|email|required_without:phone',
            'phone'    => 'nullable|string|max:20|required_without:email',
            'password' => 'nullable|string|min:8',
        ]);

        $creator    = auth()->user();
        $targetRole = $request->role;

        $allowed = match ($creator->role) {
            'super_admin' => in_array($targetRole, ['super_admin', 'admin', 'examinee']),
            'admin'       => in_array($targetRole, ['employee', 'examinee']),
            'employee'    => $targetRole === 'examinee',
            default       => false,
        };

        abort_unless($allowed, 403, 'Not allowed');

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => $targetRole === 'examinee' ? null : ($request->password ? bcrypt($request->password) : bcrypt('password')),
            'role'     => $targetRole,
        ]);

        return redirect()->route('users.index')->with('status', 'User created.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role'     => 'required|in:super_admin,admin,employee,examinee',
            'name'     => 'required|string|max:120',
            'email'    => 'nullable|email|required_without:phone',
            'phone'    => 'nullable|string|max:20|required_without:email',
            'password' => 'nullable|string|min:8',
        ]);

        $creator    = auth()->user();
        $targetRole = $data['role'];
        $allowed    = match ($creator->role) {
            'super_admin' => in_array($targetRole, ['super_admin', 'admin', 'examinee']),
            'admin'       => in_array($targetRole, ['employee', 'examinee']),
            'employee'    => $targetRole === 'examinee',
            default       => false,
        };
        abort_unless($allowed, 403, 'Not allowed');

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
