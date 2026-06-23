<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 dark:text-white">{{ __('My Reservations') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('View and manage your upcoming and past bookings.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold text-green-800 dark:text-green-300">{{ __('Success!') }}</p>
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 dark:border-gray-700 overflow-hidden">
        @if($reservations->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-gray-700 mb-4">
                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-white">{{ __('No reservations found') }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __("You haven't made any classroom reservations yet.") }}</p>
                <a href="{{ route('classrooms.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-md transition-all">{{ __('Browse Classrooms') }}</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Classroom') }}</th>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Date & Time') }}</th>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Purpose') }}</th>
                            <th class="text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-6 py-3">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($reservations as $reservation)
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
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($reservation->status === 'approved') bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-800
                                        @elseif($reservation->status === 'pending') bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-100 dark:border-yellow-800
                                        @else bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800 @endif">
                                        @if($reservation->status === 'approved') {{ __('Approved') }}
                                        @elseif($reservation->status === 'pending') {{ __('Pending') }}
                                        @else {{ __('Rejected') }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($reservations->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $reservations->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
