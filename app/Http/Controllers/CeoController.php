<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

        $userCount = User::where('role', 'user')->count();

        return view('ceo.users.index', compact(
            'users',
            'totalUsers',
            'ceoCount',
            'superadminCount',
            'adminCount',
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
    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent CEO from deleting himself
        |--------------------------------------------------------------------------
        */

        if ($user->id === Auth::id()) {

            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete profile picture
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_picture &&
            Storage::disk('public')->exists($user->profile_picture)
        ) {

            Storage::disk('public')->delete(
                $user->profile_picture
            );
        }


        $user->delete();

        return redirect()
            ->route('ceo.users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}
