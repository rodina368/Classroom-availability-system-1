<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('Classroom Availability Search') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-850 px-3 py-1 rounded-full font-medium">
                Real-time Monitoring
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ 
        showModal: false, 
        classroomId: '{{ old('classroom_id', '') }}', 
        classroomName: '', 
        classroomCapacity: '',
        classroomLocation: '',
        date: '{{ old('date', $date ?? now()->format('Y-m-d')) }}', 
        startTime: '{{ old('start_time', $startTime ?? '') }}', 
        endTime: '{{ old('end_time', $endTime ?? '') }}',
        purpose: '{{ old('purpose', '') }}'
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Validation/Conflict Errors Alert -->
            @if ($errors->has('classroom_id'))
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg shadow-md transition-all duration-300" x-data="{ showSuggestions: false }">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-4 w-full">
                            <h3 class="text-lg font-bold text-red-800">
                                {{ $errors->first('classroom_id') }}
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>The classroom <strong>{{ session('conflictClassroomName') }}</strong> is already booked from <strong>{{ session('conflictStart') }}</strong> to <strong>{{ session('conflictEnd') }}</strong>.</p>
                            </div>

                            <!-- Toggle Button for Alternatives -->
                            <div class="mt-4">
                                <button type="button" @click="showSuggestions = !showSuggestions"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-200 rounded-lg text-sm font-bold text-red-700 hover:bg-red-100 transition shadow-sm">
                                    <svg class="h-4 w-4 transition-transform duration-200" :class="showSuggestions ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    <span x-text="showSuggestions ? 'Hide Alternative Rooms' : 'Show Alternative Rooms'"></span>
                                </button>
                            </div>

                            <!-- Suggested Alternatives Section (hidden by default) -->
                            <div x-show="showSuggestions" x-transition class="mt-4 border-t border-red-200 pt-4">
                                <h4 class="text-sm font-semibold text-red-800 uppercase tracking-wider mb-3">Alternative Classrooms Available at this Time:</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Same Location Alternatives -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                        <h5 class="font-semibold text-gray-800 text-sm mb-2 flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                            Same Location ({{ session('conflictClassroomLocation') }})
                                        </h5>
                                        @if(session('sameLocationAlternatives') && count(session('sameLocationAlternatives')) > 0)
                                            <ul class="space-y-2">
                                                @foreach(session('sameLocationAlternatives') as $alt)
                                                    <li class="flex justify-between items-center text-xs text-gray-600 bg-gray-50 p-2 rounded hover:bg-gray-100 transition">
                                                        <span><strong>{{ $alt['name'] }}</strong> (Seats: {{ $alt['capacity'] }})</span>
                                                        <button type="button" 
                                                            @click="
                                                                classroomId = '{{ $alt['id'] }}';
                                                                classroomName = '{{ $alt['name'] }}';
                                                                classroomLocation = '{{ $alt['location'] }}';
                                                                classroomCapacity = '{{ $alt['capacity'] }}';
                                                                showModal = true;
                                                            " 
                                                            class="px-2 py-1 bg-indigo-600 text-white rounded font-medium hover:bg-indigo-700 transition">
                                                            Book
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-xs text-gray-500 italic">No alternative classrooms found in this location.</p>
                                        @endif
                                    </div>

                                    <!-- Similar Capacity Alternatives -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                        <h5 class="font-semibold text-gray-800 text-sm mb-2 flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                            Similar Capacity (Seats: {{ session('conflictClassroomCapacity') }})
                                        </h5>
                                        @if(session('similarCapacityAlternatives') && count(session('similarCapacityAlternatives')) > 0)
                                            <ul class="space-y-2">
                                                @foreach(session('similarCapacityAlternatives') as $alt)
                                                    <li class="flex justify-between items-center text-xs text-gray-600 bg-gray-50 p-2 rounded hover:bg-gray-100 transition">
                                                        <span><strong>{{ $alt['name'] }}</strong> ({{ $alt['location'] }}, Seats: {{ $alt['capacity'] }})</span>
                                                        <button type="button" 
                                                            @click="
                                                                classroomId = '{{ $alt['id'] }}';
                                                                classroomName = '{{ $alt['name'] }}';
                                                                classroomLocation = '{{ $alt['location'] }}';
                                                                classroomCapacity = '{{ $alt['capacity'] }}';
                                                                showModal = true;
                                                            " 
                                                            class="px-2 py-1 bg-indigo-600 text-white rounded font-medium hover:bg-indigo-700 transition">
                                                            Book
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-xs text-gray-500 italic">No alternative classrooms of similar capacity found.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Panel Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 flex items-center">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter Parameters
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Provide date, time-frame, location, and size requirements to inspect room availability.</p>
                </div>

                <div class="p-6 md:p-8">
                    <form method="GET" action="{{ route('availability.search') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <!-- Date -->
                        <div class="space-y-1">
                            <label for="search_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Date</label>
                            <input type="date" name="date" id="search_date" value="{{ $date }}" 
                                @change="date = $el.value"
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>

                        <!-- Start Time -->
                        <div class="space-y-1">
                            <label for="search_start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Start Time</label>
                            <input type="time" name="start_time" id="search_start_time" value="{{ $startTime }}" 
                                @change="startTime = $el.value"
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>

                        <!-- End Time -->
                        <div class="space-y-1">
                            <label for="search_end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">End Time</label>
                            <input type="time" name="end_time" id="search_end_time" value="{{ $endTime }}" 
                                @change="endTime = $el.value"
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>

                        <!-- Location -->
                        <div class="space-y-1">
                            <label for="search_location" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Building / Campus</label>
                            <select name="location" id="search_location" 
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ $location == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Capacity -->
                        <div class="space-y-1">
                            <label for="search_capacity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Min Capacity (seats)</label>
                            <input type="number" name="capacity" id="search_capacity" value="{{ $capacity }}" min="1" placeholder="e.g. 20"
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>

                        <!-- Actions Row -->
                        <div class="md:col-span-2 lg:col-span-5 flex justify-end gap-3 pt-2">
                            <a href="{{ route('availability.search') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg text-sm transition">
                                Reset Filters
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-md hover:shadow-lg transition">
                                Search Classrooms
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white px-1">Classroom Inventories</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($classrooms as $classroom)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between">
                            <div class="p-6">
                                <!-- Top Badges -->
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-md">
                                        {{ $classroom->location }}
                                    </span>
                                    
                                    @if($date && $startTime && $endTime)
                                        @if($classroom->status == 'free')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/20 text-green-800 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                Free
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-950/20 text-red-800 dark:text-red-400">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                                Occupied
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-400">
                                            Status Unknown
                                        </span>
                                    @endif
                                </div>

                                <!-- Room Header -->
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $classroom->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Capacity: {{ $classroom->capacity }} seats
                                </p>

                                <!-- Equipment Tags -->
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    @if($classroom->equipment)
                                        @foreach($classroom->equipment as $eq)
                                            <span class="px-2 py-0.5 bg-indigo-50/50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 text-[10px] font-medium rounded border border-indigo-100 dark:border-indigo-900/50">
                                                {{ ucfirst($eq) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">No special equipment</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-4">
                                <a href="{{ route('classrooms.show', $classroom) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold transition">
                                    Full Schedule &rarr;
                                </a>

                                <button type="button" 
                                    @click="
                                        classroomId = '{{ $classroom->id }}';
                                        classroomName = '{{ $classroom->name }}';
                                        classroomLocation = '{{ $classroom->location }}';
                                        classroomCapacity = '{{ $classroom->capacity }}';
                                        showModal = true;
                                    "
                                    class="px-4 py-1.5 text-xs font-bold rounded-lg border border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition shadow-sm">
                                    Book Room
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">No rooms found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try modifying your location or capacity filters to find matching classrooms.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Booking Modal Backdrop and Card -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 transform transition-all duration-300"
                @click.away="showModal = false">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-indigo-600 dark:bg-gray-900 text-white flex justify-between items-center border-b border-transparent dark:border-gray-700">
                    <div>
                        <h3 class="font-bold text-lg">Book Classroom</h3>
                        <p class="text-xs text-indigo-200 dark:text-indigo-300">Confirm booking details below</p>
                    </div>
                    <button type="button" @click="showModal = false" class="text-white hover:text-indigo-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="classroom_id" :value="classroomId">
                    <input type="hidden" name="search_capacity" value="{{ $capacity }}">
                    <input type="hidden" name="search_location" value="{{ $location }}">

                    <!-- Selected Room Details -->
                    <div class="bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-150 dark:border-indigo-900/30 p-4 rounded-xl flex justify-between items-center">
                        <div>
                            <h4 class="font-extrabold text-indigo-900 dark:text-indigo-300 text-sm" x-text="classroomName"></h4>
                            <p class="text-xs text-indigo-700 dark:text-indigo-400" x-text="classroomLocation"></p>
                        </div>
                        <span class="px-2.5 py-1 bg-white dark:bg-gray-900 text-indigo-850 dark:text-white text-xs font-bold rounded-lg shadow-sm border border-indigo-100 dark:border-gray-700">
                            Seats: <span x-text="classroomCapacity"></span>
                        </span>
                    </div>

                    <!-- Date & Times -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Date</label>
                            <input type="date" name="date" x-model="date" required
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Start Time</label>
                            <input type="time" name="start_time" x-model="startTime" required
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">End Time</label>
                            <input type="time" name="end_time" x-model="endTime" required
                                class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Purpose / Course Name</label>
                        <input type="text" name="purpose" x-model="purpose" placeholder="e.g. CS 101 Lecture, Meeting" required
                            class="w-full rounded-lg border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-150 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-150 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg text-sm transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-md hover:shadow-lg transition">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
