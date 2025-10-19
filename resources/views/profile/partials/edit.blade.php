@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-4xl font-extrabold mb-8 text-gray-900">👤 Profile Settings</h1>

    @if (session('status'))
        <div class="bg-green-500 text-white p-3 rounded-xl mb-6">
            {{ session('status') }}
        </div>
    @endif

    {{-- ===== Update Profile Info ===== --}}
    <div class="bg-white rounded-2xl p-6 shadow-md mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Update Profile Information</h2>
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full p-3 rounded-lg border @error('name') border-red-500 @else border-gray-300 @enderror
                       bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full p-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror
                       bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md">
                💾 Save Changes
            </button>
        </form>
    </div>

    {{-- ===== Update Password ===== --}}
    <div class="bg-white rounded-2xl p-6 shadow-md mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Change Password</h2>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password"
                       class="w-full p-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-500">
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 mb-1">New Password</label>
                <input type="password" name="password"
                       class="w-full p-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-500">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full p-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-500">
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded-lg shadow-md">
                🔑 Update Password
            </button>
        </form>
    </div>

    {{-- ===== Delete Account ===== --}}
    <div class="bg-red-100 rounded-2xl p-6 shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-red-700">Danger Zone</h2>
        <p class="text-gray-700 mb-4">Deleting your account is permanent. All your data will be lost.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.')"
                    class="bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-lg shadow-md">
                🗑 Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
