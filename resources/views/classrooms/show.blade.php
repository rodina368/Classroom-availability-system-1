<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('classrooms.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-limu-blue transition-colors mb-4">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Classrooms
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 flex items-center gap-3">
                    {{ $classroom->name }} Details
                    @php
                        $isFavourited = Auth::user()->favourites->contains($classroom->id);
                    @endphp
                    <form action="{{ $isFavourited ? route('favourites.destroy', $classroom) : route('favourites.store', $classroom) }}" method="POST" class="inline-block">
                        @csrf
                        @if($isFavourited) @method('DELETE') @endif
                        <button type="submit" class="p-1.5 rounded-full hover:bg-gray-100 transition-colors {{ $isFavourited ? 'text-red-500' : 'text-gray-300 hover:text-red-400' }}" title="{{ $isFavourited ? 'Remove from Favourites' : 'Add to Favourites' }}">
                            <svg class="w-6 h-6" fill="{{ $isFavourited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </form>
                </h2>
                <p class="text-gray-500 text-sm mt-1">View schedule and classroom information</p>
            </div>
            
            <button x-data @click="$dispatch('open-booking-modal')" class="inline-flex items-center gap-2 px-6 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-[0_4px_14px_0_rgba(123,63,228,0.39)] transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Book this Room
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-start">
            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-bold text-green-800">Success!</p>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->has('classroom_id'))
        {{-- Conflict Alert Banner --}}
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-start gap-4 p-5 border-b border-red-100">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-red-800 text-base">⚠️ Booking Conflict Detected</p>
                    <p class="text-sm text-red-700 mt-1">{{ $errors->first('classroom_id') }}</p>
                    @if(session('conflictStart') && session('conflictEnd'))
                        <p class="text-xs text-red-500 mt-1 font-medium">
                            Conflicting slot: {{ \Carbon\Carbon::parse(session('conflictStart'))->format('M d, Y • h:i A') }} – {{ \Carbon\Carbon::parse(session('conflictEnd'))->format('h:i A') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Same Location Alternatives --}}
            @if(session('sameLocationAlternatives') && count(session('sameLocationAlternatives')) > 0)
                <div class="p-5 border-b border-red-100">
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Available in Same Location ({{ session('conflictClassroomLocation') }})
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(session('sameLocationAlternatives') as $alt)
                            <a href="{{ route('classrooms.show', $alt['id']) }}"
                               class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-red-200 hover:border-limu-blue hover:bg-blue-50 rounded-xl text-sm font-semibold text-gray-800 hover:text-limu-blue transition-all group shadow-sm">
                                <span>{{ $alt['name'] }}</span>
                                <span class="text-xs font-normal text-gray-400 group-hover:text-blue-400">{{ $alt['capacity'] }} seats</span>
                                <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-limu-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Similar Capacity Alternatives --}}
            @if(session('similarCapacityAlternatives') && count(session('similarCapacityAlternatives')) > 0)
                <div class="p-5">
                    <p class="text-xs font-bold text-orange-600 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Other Available Classrooms (Similar Capacity)
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(session('similarCapacityAlternatives') as $alt)
                            <a href="{{ route('classrooms.show', $alt['id']) }}"
                               class="flex items-center justify-between p-3 bg-white border border-gray-100 hover:border-limu-blue/50 hover:bg-blue-50/50 rounded-xl transition-all group shadow-sm">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-limu-blue truncate">{{ $alt['name'] }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $alt['location'] }}</p>
                                </div>
                                <span class="ml-3 flex-shrink-0 px-2 py-0.5 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-100">
                                    {{ $alt['capacity'] }} seats
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if((!session('sameLocationAlternatives') || count(session('sameLocationAlternatives')) === 0) && (!session('similarCapacityAlternatives') || count(session('similarCapacityAlternatives')) === 0))
                <div class="p-5">
                    <p class="text-sm text-red-600 font-medium">No alternative classrooms are available for the requested time slot. Please try a different time.</p>
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Classroom Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Information
                    </h3>
                    
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-limu-blue mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase">Capacity</p>
                                <p class="text-sm font-medium text-gray-900">{{ $classroom->capacity }} people</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-limu-blue mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase">Location</p>
                                <p class="text-sm font-medium text-gray-900">{{ $classroom->location }}</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-limu-blue mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase">Equipment</p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    @if($classroom->equipment)
                                        @foreach($classroom->equipment as $eq)
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded border border-gray-200">
                                                {{ str_replace('_', ' ', ucfirst($eq)) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-sm text-gray-500">None</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Column: Schedule --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Upcoming Schedule
                    </h3>
                </div>

                @if($reservations->isEmpty())
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900">No upcoming reservations</h4>
                        <p class="text-sm text-gray-500 mt-1">This classroom is completely free in the coming days.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Date & Time</th>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Purpose / Reserved By</th>
                                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($reservations as $reservation)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900">{{ $reservation->start_time->format('M d, Y') }}</span>
                                                <span class="text-xs text-gray-500 mt-0.5">
                                                    {{ $reservation->start_time->format('h:i A') }} - {{ $reservation->end_time->format('h:i A') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-900">{{ $reservation->purpose }}</span>
                                                <span class="text-xs text-gray-500 mt-0.5">{{ $reservation->user->name ?? 'Unknown User' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold
                                                @if($reservation->status === 'approved') bg-green-50 text-green-700 border border-green-100
                                                @elseif($reservation->status === 'pending') bg-yellow-50 text-yellow-700 border border-yellow-100
                                                @else bg-red-50 text-red-700 border border-red-100 @endif">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Booking Modal (Triggered by Alpine event or on error) --}}
    <div x-data="{ showModal: {{ $errors->has('classroom_id') ? 'true' : 'false' }} }" 
         @open-booking-modal.window="showModal = true"
         x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
         x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 transform transition-all duration-300"
            @click.away="showModal = false">

            <div class="px-6 py-4 bg-limu-blue text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">Book Classroom</h3>
                    <p class="text-xs text-blue-200">Confirm booking details below</p>
                </div>
                <button type="button" @click="showModal = false" class="text-white hover:text-blue-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @if($errors->has('classroom_id'))
                <div class="px-6 py-3 bg-red-50 border-b border-red-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <p class="text-xs font-semibold text-red-700">Time slot is taken — adjust the date or time and try again.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                <input type="hidden" name="search_capacity" value="{{ $classroom->capacity }}">

                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex justify-between items-center">
                    <div>
                        <h4 class="font-extrabold text-limu-blue text-sm">{{ $classroom->name }}</h4>
                        <p class="text-xs text-blue-600">{{ $classroom->location }}</p>
                    </div>
                    <span class="px-2.5 py-1 bg-white text-limu-blue text-xs font-bold rounded-lg shadow-sm border border-blue-100">
                        Seats: {{ $classroom->capacity }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Date</label>
                        <input type="date" name="date" required value="{{ old('date', now()->format('Y-m-d')) }}"
                            class="w-full rounded-full border-gray-200 text-sm focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2 @error('date') border-red-300 @enderror">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Start Time</label>
                        <input type="time" name="start_time" required value="{{ old('start_time') }}"
                            class="w-full rounded-full border-gray-200 text-sm focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2 @error('start_time') border-red-300 @enderror">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">End Time</label>
                        <input type="time" name="end_time" required value="{{ old('end_time') }}"
                            class="w-full rounded-full border-gray-200 text-sm focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2 @error('end_time') border-red-300 @enderror">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Purpose / Course Name</label>
                    <input type="text" name="purpose" placeholder="e.g. CS 101 Lecture, Meeting" required value="{{ old('purpose') }}"
                        class="w-full rounded-full border-gray-200 text-sm focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-full text-sm transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-md hover:shadow-[0_4px_14px_0_rgba(123,63,228,0.39)] transition-all">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

