<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CeoController extends Controller
{
    //
    public function dashboard()
    {
        return view('ceo.dashboard');
    }
     /**
     * Display all users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Role filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {

            $query->where('role', $request->role);
        }

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $ceoCount = User::where('role', 'ceo')->count();

        $superadminCount = User::where('role', 'superadmin')->count();

        $adminCount = User::where('role', 'admin')->count();

        $qualityCount = User::where('role', 'quality')->count();

        $userCount = User::where('role', 'user')->count();

        return view('ceo.users.index', compact(
            'users',
            'totalUsers',
            'ceoCount',
            'superadminCount',
            'adminCount',
            'qualityCount',
            'userCount'
        ));
    }


    /**
     * Show create user form.
     */
    public function create()
    {
        return view('ceo.users.create');
    }


    /**
     * Store new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'matricule' => [
                'required',
                'string',
                'max:100',
                'unique:users,matricule',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                Rule::in([
                    'ceo',
                    'superadmin',
                    'admin',
                    'quality',
                    'user',
                ]),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Create user
        |--------------------------------------------------------------------------
        */

        $user = new User();

        $user->name = $validated['name'];

        $user->matricule = $validated['matricule'];

        $user->email = $validated['email'];

        $user->role = $validated['role'];

        $user->password = Hash::make($validated['password']);

        /*
        |--------------------------------------------------------------------------
        | Profile picture
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_picture')) {

            $user->profile_picture = $request
                ->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        $user->save();

        return redirect()
            ->route('ceo.users.index')
            ->with('success', 'User created successfully.');
    }


    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        return view('ceo.users.edit', compact('user'));
    }


    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'matricule' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'matricule')
                    ->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in([
                    'ceo',
                    'superadmin',
                    'admin',
                    'quality',
                    'user',
                ]),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Update basic information
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];

        $user->matricule = $validated['matricule'];

        $user->email = $validated['email'];

        $user->role = $validated['role'];


        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            $user->password = Hash::make(
                $validated['password']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | New profile picture
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('profile_picture')) {

            /*
            | Delete old image
            */

            if (
                $user->profile_picture &&
                Storage::disk('public')->exists($user->profile_picture)
            ) {
                Storage::disk('public')->delete(
                    $user->profile_picture
                );
            }

            /*
            | Store new image
            */

            $user->profile_picture = $request
                ->file('profile_picture')
                ->store('profile-pictures', 'public');
        }


        $user->save();

        return redirect()
            ->route('ceo.users.index')
            ->with('success', 'User updated successfully.');
    }


    /**
     * Delete profile picture.
     */
    public function deletePicture(User $user)
    {
        if (
            $user->profile_picture &&
            Storage::disk('public')->exists($user->profile_picture)
        ) {

            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }

        $user->profile_picture = null;

        $user->save();

        return back()->with(
            'success',
            'Profile picture deleted successfully.'
        );
    }


    /**
     * Delete user.
     */

/**
 * Delete a user after validating the CEO security code.
 */
public function destroy(Request $request, User $user)
{
    if ($user->id === Auth::id()) {
        return back()->with(
            'error',
            'You cannot delete your own account.'
        );
    }

    $request->validate([
        'security_code' => ['required', 'string', 'max:255'],
    ]);

    $configuredCode = (string) config('app.ceo_delete_security_code');
    $enteredCode = (string) $request->input('security_code');

    if (
        $configuredCode === '' ||
        ! hash_equals($configuredCode, $enteredCode)
    ) {
        throw ValidationException::withMessages([
            'security_code' => 'Incorrect security code. The account was not deleted.',
        ]);
    }

    if (
        $user->profile_picture &&
        Storage::disk('public')->exists($user->profile_picture)
    ) {
        Storage::disk('public')->delete($user->profile_picture);
    }

    $user->delete();

    return redirect()
        ->route('ceo.users.index')
        ->with('success', 'User deleted successfully.');
}
    /**
 * Activate or deactivate a user account.
 */
public function toggleAccountStatus(User $user)
{
    // Never allow the CEO to deactivate their own account.
    if ($user->id === Auth::id()) {
        return back()->with(
            'error',
            'You cannot deactivate your own account.'
        );
    }

    // Change the status.
    $user->is_active = ! $user->is_active;
    $user->save();

    $isActive = $user->is_active;

    $subject = $isActive
        ? 'TapApp account activated'
        : 'TapApp account deactivated';

    $message = $isActive
        ? "Hello {$user->name},\n\n"
            . "Your TapApp account has been activated by the CEO. "
            . "You can now log in using your usual credentials."
        : "Hello {$user->name},\n\n"
            . "Your TapApp account has been deactivated by the CEO. "
            . "You cannot log in while your account is inactive. "
            . "Please contact your administrator if you need assistance.";

    // The database status is saved even if email delivery fails.
    try {
        Mail::raw($message, function ($mail) use ($user, $subject) {
            $mail->to($user->email)
                ->subject($subject);
        });

        $emailMessage = ' Notification email sent.';
    } catch (Throwable $e) {
        Log::error('TapApp account status email failed.', [
            'user_id' => $user->id,
            'exception' => $e->getMessage(),
        ]);

        $emailMessage =
            ' However, the notification email could not be sent.';
    }

    return back()->with(
        'success',
        ($isActive
            ? 'Account activated successfully.'
            : 'Account deactivated successfully.')
        . $emailMessage
    );
}

}
