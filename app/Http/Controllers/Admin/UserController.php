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
    public function index(Request $request)
    {
        $query = User::with('categories', 'userSessions');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhereJsonContains('class_levels', $search)
                  ->orWhereHas('categories', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('name_mm', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by online status
        if ($request->filled('online')) {
            if ($request->get('online') === 'yes') {
                $query->whereNotNull('current_session_id');
            } elseif ($request->get('online') === 'no') {
                $query->whereNull('current_session_id');
            }
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function($categoryQuery) use ($request) {
                $categoryQuery->where('categories.id', $request->get('category'));
            });
        }
        
        // Filter by class level
        if ($request->filled('class_level')) {
            $query->whereJsonContains('class_levels', $request->get('class_level'));
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['created_at', 'name', 'email', 'last_login_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'last_login_at') {
                $query->orderByRaw('last_login_at IS NULL, last_login_at ' . $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $users = $query->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        // Get unique class levels for filter
        $classLevels = User::whereNotNull('class_levels')
            ->get()
            ->pluck('class_levels')
            ->flatten()
            ->unique()
            ->sort()
            ->values();
        
        return view('admin.users.index', compact('users', 'categories', 'classLevels'));
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
    
    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(User $user)
    {
        // Don't allow deactivating the current admin user
        if ($user->id === auth()->id() && $user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.'
            ], 400);
        }
        
        $user->update(['is_active' => !$user->is_active]);
        
        // If deactivating, also clear sessions
        if (!$user->is_active) {
            UserSession::where('user_id', $user->id)->update(['is_active' => false]);
            $user->update(['current_session_id' => null]);
        }
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "User {$status} successfully.",
            'is_active' => $user->is_active
        ]);
    }
}
