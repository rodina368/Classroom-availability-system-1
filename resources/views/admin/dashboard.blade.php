<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Admin Dashboard') }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Overview of system usage, classroom availability, and recent activities.') }}</p>
                </div>
                <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border border-transparent rounded-lg font-bold text-sm text-white tracking-wide transition shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add Classroom') }}
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Total Classrooms -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Total Classrooms') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $totalClassrooms }}</h4>
                    </div>
                </div>

                <!-- Available Classrooms -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Available Now') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $availableClassrooms }}</h4>
                    </div>
                </div>

                <!-- Occupied Classrooms -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Occupied Now') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $occupiedClassrooms }}</h4>
                    </div>
                </div>

                <!-- Upcoming Bookings Today -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Upcoming Today') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $upcomingBookingsToday }}</h4>
                    </div>
                </div>

                <!-- Number of Students -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Students') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $studentsCount }}</h4>
                    </div>
                </div>

                <!-- Number of Instructors -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Instructors') }}</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $instructorsCount }}</h4>
                    </div>
                </div>

                <!-- Utilization Rate -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-150 flex items-center gap-4 md:col-span-2 hover:shadow-md transition">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-2">{{ __('Current Utilization Rate') }}</p>
                        <div class="flex items-end gap-3">
                            <h4 class="text-3xl font-bold text-gray-900">{{ $utilizationRate }}%</h4>
                            <p class="text-sm text-gray-500 mb-1">{{ __('of active rooms are currently in use') }}</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $utilizationRate }}%"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Booking Activities -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden mt-8">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-indigo-50/20 border-b border-gray-150 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Recent Booking Activities') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Latest classroom reservations made in the system.') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-150">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Classroom') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Date & Time') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Purpose') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                                {{ strtoupper(substr($booking->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $booking->user->name ?? __('Unknown User') }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst(__($booking->user->role ?? 'N/A')) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $booking->classroom->name ?? __('Deleted Classroom') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->start_time->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->start_time->format('h:i A') }} - {{ $booking->end_time->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $booking->purpose }}">
                                        {{ $booking->purpose }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($booking->status == 'approved')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ __('Approved') }}
                                            </span>
                                        @elseif($booking->status == 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                {{ __('Pending') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                {{ __(ucfirst($booking->status)) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 italic bg-gray-50">
                                        {{ __('No recent booking activities found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
