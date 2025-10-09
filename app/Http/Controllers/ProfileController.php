<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Show the profile edit form
    public function edit(Request $request)
    {
        $user = $request->user(); // same as auth()->user()
        return view('profile.edit', compact('user'));
    }

    // Save profile changes
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // password is optional; if given it must be confirmed
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    // (Optional) Delete account
    public function destroy(Request $request)
    {
        $user = $request->user();

        // If you want to require password confirmation, add validation here

        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted.');
    }
}
