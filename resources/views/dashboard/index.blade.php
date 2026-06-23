<x-app-layout>
    <div class="mb-8">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white">{{ __('Dashboard') }}</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('Overview of your activity and statistics.') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Upcoming Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 dark:border-gray-700 p-6 relative overflow-hidden flex items-center justify-between">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-limu-blue opacity-50"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Upcoming Reservations') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $upcomingCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/30 text-limu-blue flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- Past Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 dark:border-gray-700 p-6 relative overflow-hidden flex items-center justify-between">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-400 opacity-50"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Past Reservations') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pastCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Favourites Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 dark:border-gray-700 p-6 relative overflow-hidden flex items-center justify-between">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-400 opacity-50"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('Saved Favourites') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $favouritesCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/30 text-red-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Upcoming Bookings Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-limu-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('Next Upcoming Reservations') }}
            </h3>
            <a href="{{ route('reservations.index') }}" class="text-sm font-semibold text-limu-blue hover:text-limu-blue-light transition-colors">{{ __('View All') }} &rarr;</a>
        </div>

        @if($nextReservations->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-gray-700 mb-4">
                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ __('No upcoming reservations') }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __("You don't have any bookings scheduled.") }}</p>
                <a href="{{ route('classrooms.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-md hover:shadow-[0_4px_14px_0_rgba(123,63,228,0.39)] transition-all">{{ __('Book a Classroom') }}</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Classroom') }}</th>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Date & Time') }}</th>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Purpose') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($nextReservations as $reservation)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <a href="{{ route('classrooms.show', $reservation->classroom) }}" class="text-sm font-bold text-limu-blue hover:underline">{{ $reservation->classroom->name }}</a>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $reservation->classroom->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $reservation->start_time->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $reservation->start_time->format('h:i A') }} - {{ $reservation->end_time->format('h:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->purpose }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
