<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('Admin User Role Management') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-850 px-3 py-1 rounded-full font-medium">
                {{ __('Authorization Controls') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm text-green-800 dark:text-green-300 transition-all duration-300">
                    <p class="font-bold">{{ __('Success!') }}</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm text-red-800 transition-all duration-300">
                    <p class="font-bold">{{ __('Action Blocked') }}</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Users Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-150 dark:border-gray-700 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-indigo-50/20 dark:from-gray-800 dark:to-gray-800 border-b border-gray-150 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Registered Accounts') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Update system roles to grant or revoke administrative and lecturer privileges.') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-150 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Email Address') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('University ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Department') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Assigned Role') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-150 dark:divide-gray-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white flex items-center">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="ms-2 px-1.5 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-[10px] font-semibold rounded">
                                                {{ __('You') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        {{ $user->university_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->department }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <select name="role" onchange="this.form.submit()" 
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                                class="rounded-lg border-gray-250 dark:border-gray-700 text-xs font-semibold focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm py-1.5 transition
                                                @if($user->role === 'administrator') bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/20 dark:text-purple-300 dark:border-purple-800
                                                @elseif($user->role === 'lecturer') bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/20 dark:text-blue-300 dark:border-blue-800
                                                @else bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 @endif">
                                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                                                <option value="lecturer" {{ $user->role === 'lecturer' ? 'selected' : '' }}>{{ __('Lecturer') }}</option>
                                                <option value="administrator" {{ $user->role === 'administrator' ? 'selected' : '' }}>{{ __('Administrator') }}</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-150">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
