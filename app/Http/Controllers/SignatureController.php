<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'signature' => [
                'required',
                'string',
                'regex:/^data:image\/png;base64,/',
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Remove old signature
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature);
        }

        // Remove base64 prefix
        $image = preg_replace(
            '/^data:image\/png;base64,/',
            '',
            $request->signature
        );

        $image = base64_decode($image);

        if ($image === false) {
            return back()->withErrors([
                'signature' => 'Invalid signature.'
            ]);
        }

        $filename = 'signatures/' . $user->id . '_' . time() . '.png';

        Storage::disk('public')->put($filename, $image);

        $user->signature = $filename;
        $user->save();

        return back()->with('success', 'Signature saved successfully.');
    }
}
