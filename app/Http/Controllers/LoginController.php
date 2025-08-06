<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Log;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            // 'role'     => 'required|in:user', // Restrict admin registration
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->password    = Hash::make($request->password);
        $user->role        = 'user';
        $user->is_approved = 0;
        $user->save();

        return redirect()->route('login')->with('success', 'Registered successfully! Please wait for admin approval.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_approved == 0 && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['Your account is pending admin approval.']);
            }

            return $user->role === 'admin'
                ? redirect()->route('admin.dashboard')->with('message', 'You are logged in as admin!')
                : redirect()->route('user.dashboard')->with('message', 'You are logged in successfully!');
        }

        return back()->withErrors(['Invalid credentials.']);
    }

 public function adminDashboard(Request $request)
{
    $totalUsers = User::count();
    $approvedUserCount = User::where('is_approved', 1)->count();
    $pendingUserCount = User::where('is_approved', 0)->count();

    $searchName = $request->input('name');
    $searchEmail = $request->input('email');
    $filterRole = $request->input('role');

    // Approved users (only role=user, is_approved=1)
    $approvedUsersQuery = User::where('is_approved', 1)->where('role', 'user');
    if ($searchName) {
        $approvedUsersQuery->where('name', 'like', "%{$searchName}%");
    }
    if ($searchEmail) {
        $approvedUsersQuery->where('email', 'like', "%{$searchEmail}%");
    }
    $approvedUsers = $approvedUsersQuery->paginate(10, ['*'], 'approved')->appends($request->query());

    // Pending users (role=user or admin, is_approved=0)
    $pendingUsersQuery = User::where('is_approved', 0);
    if ($searchName) {
        $pendingUsersQuery->where('name', 'like', "%{$searchName}%");
    }
    if ($searchEmail) {
        $pendingUsersQuery->where('email', 'like', "%{$searchEmail}%");
    }
    if ($filterRole) {
        $pendingUsersQuery->where('role', $filterRole);
    }
    $pendingUsers = $pendingUsersQuery->paginate(10, ['*'], 'pending')->appends($request->query());

    $files = Upload::orderby('created_at', 'desc')->get();

    return view('admindashboard', compact(
    'totalUsers',
    'approvedUserCount',
    'pendingUserCount',
    'approvedUsers',
    'pendingUsers',
    'searchName',
    'searchEmail',
    'filterRole',
    'files'
));

}

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = 1;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User approved successfully!');
    }

    public function userDashboard()
    {
        return view('userdashboard');
    }

    public function showForgotForm(Request $request)
    {
        return view('forgetpassword');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('resetpassword', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show form for authenticated users to create/set a new password.
     */
    public function showSetPasswordForm()
    {
        return view('auth.set-password');
    }

    /**
     * Store new password for authenticated user.
     */
  public function storeNewPass(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|confirmed|min:8',
    ]);

    Log::info('Reset password request', [
        'email' => $request->email,
    ]);

    $user = User::where('email', $request->email)->firstOrFail();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('status', 'Password updated successfully.');
}
// View user details
public function viewUser($id)
{
    $user = User::findOrFail($id);
    return view('view', compact('user'));
}

// Show edit form
public function editUser($id)
{
    $user = User::findOrFail($id);
    return view('edit', compact('user'));
}

// Update user
public function updateUser(Request $request, $id)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        // 'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $user = User::findOrFail($id);
    $user->name  = $request->name;
    $user->email = $request->email;
    $user->role  = 'user';
    $user->save();

    return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
}

// Delete user
public function deleteUser($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
}


public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:2048',
        'title' => 'required|max:255',
    ]);

    $file = $request->file('file');
    $path = $file->store('uploads', 'public');
    
// File view and delete

    Upload::create([
        'title' =>  $request->title,
        'filename' => $file->getClientOriginalName(),
        'path' => $path,
    ]);

    return redirect()->route('admin.dashboard')
        ->with('success', 'File uploaded successfully.');
}

public function delete($id,Request $request)
{
    $file= Upload::findOrFail($id);
    $file->delete();
    $file = $request->input('file');

    if (\Storage::disk('public')->exists($file)) {
        \Storage::disk('public')->delete($file);
        return back()->with('success', 'File deleted successfully.');
    }

    return redirect()->route('admin.dashboard')->with('success', 'file  deleted successfully.');
}
}
