<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('categories')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.users.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,admin',
            'class_levels' => 'nullable|array',
            'class_levels.*' => 'string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_active'] = $request->has('is_active');
        $data['email_verified_at'] = now();
        
        // Handle class levels - set to empty array if not provided
        $data['class_levels'] = $request->class_levels ?? [];

        $user = User::create($data);

        // Assign categories to user
        if ($request->has('categories')) {
            $user->categories()->sync($request->categories);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('userSessions');
        $activeSessions = $user->userSessions()->where('is_active', true)->get();
        return view('admin.users.show', compact('user', 'activeSessions'));
    }

    public function edit(User $user)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $user->load('categories');
        return view('admin.users.edit', compact('user', 'categories'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,admin',
            'class_levels' => 'nullable|array',
            'class_levels.*' => 'string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $data = $request->all();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        
        $data['is_active'] = $request->has('is_active');
        
        // Handle class levels - set to empty array if not provided
        $data['class_levels'] = $request->class_levels ?? [];

        $user->update($data);

        // Update category assignments
        if ($request->has('categories')) {
            $user->categories()->sync($request->categories);
        } else {
            $user->categories()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Don't allow deleting the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Deactivate all user sessions
        UserSession::where('user_id', $user->id)->delete();
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
