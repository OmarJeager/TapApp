<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\View\View;

class UserController extends Controller
{
    
    public function index()
    {

        return view('user.dashboard');
    }

   public function uploadPicture(Request $request)
{
    $request->validate([
        'picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',

    ]);

    /** @var User $user */
    $user = Auth::user();

    // Delete old picture
    if ($user->profile_picture) {
        Storage::disk('public')->delete($user->profile_picture);
    }

    // Save new picture
    $filename = 'profile-pictures/' . Str::random(40) . '.jpg';

    Storage::disk('public')->put(
        $filename,
        file_get_contents($request->file('picture'))
    );

    // Save path in database
    $user->profile_picture = $filename;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile picture updated successfully.',
    ]);
}
}
