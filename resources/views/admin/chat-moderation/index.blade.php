@extends('layouts.app')

@section('title', 'Admin Chat Moderation')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Chat Moderation</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">
            Mute or unmute users from the live chat with a reason and an end time.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-xl bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">User</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Role</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Chat Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Reason</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)
                    <tr class="align-top">
                        <td class="px-4 py-4 text-gray-900 dark:text-white font-medium">
                            {{ $user->name }}
                        </td>

                        <td class="px-4 py-4 text-gray-700 dark:text-gray-300">
                            {{ $user->email }}
                        </td>

                        <td class="px-4 py-4">
                            @if($user->is_admin)
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-800">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800">
                                    User
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            @if($user->isChatMuted())
                                <div class="text-red-600 font-semibold">Muted</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    Until: {{ $user->chat_muted_until?->format('Y-m-d H:i') }}
                                </div>
                            @else
                                <span class="text-green-600 font-semibold">Active</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 max-w-xs">
                            {{ $user->chat_mute_reason ?: '—' }}
                        </td>

                        <td class="px-4 py-4">
                            @if(!$user->is_admin)
                                <form method="POST" action="{{ route('admin.chat-moderation.mute', $user) }}" class="space-y-3 mb-3">
                                    @csrf

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                            Mute until
                                        </label>
                                        <input
                                            type="datetime-local"
                                            name="mute_until"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3 py-2"
                                            required
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                                            Reason
                                        </label>
                                        <textarea
                                            name="reason"
                                            rows="3"
                                            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-3 py-2"
                                            placeholder="Explain why this user is muted..."
                                            required
                                        ></textarea>
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 transition"
                                    >
                                        Mute User
                                    </button>
                                </form>

                                @if($user->isChatMuted())
                                    <form method="POST" action="{{ route('admin.chat-moderation.unmute', $user) }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="w-full rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 transition"
                                        >
                                            Remove Mute
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">Admins cannot be muted here.</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection