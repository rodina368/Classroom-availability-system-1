<x-app-layout>
    <div x-data="{
        viewMode: 'grid',
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

        {{-- Validation/Conflict Errors Alert --}}
        @if ($errors->has('classroom_id'))
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl shadow-md mb-6 transition-all duration-300" x-data="{ showSuggestions: false }">
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
                            <p>{!! __('The classroom <strong>:name</strong> is already booked from <strong>:start</strong> to <strong>:end</strong>.', ['name' => session('conflictClassroomName'), 'start' => session('conflictStart'), 'end' => session('conflictEnd')]) !!}</p>
                        </div>

                        <div class="mt-4">
                            <button type="button" @click="showSuggestions = !showSuggestions"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-red-200 rounded-lg text-sm font-bold text-red-700 hover:bg-red-100 transition shadow-sm">
                                <svg class="h-4 w-4 transition-transform duration-200" :class="showSuggestions ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span x-text="showSuggestions ? 'Hide Alternative Rooms' : 'Show Alternative Rooms'"></span>
                            </button>
                        </div>

                        <div x-show="showSuggestions" x-transition class="mt-4 border-t border-red-200 pt-4">
                            <h4 class="text-sm font-semibold text-red-800 uppercase tracking-wider mb-3">Alternative Classrooms Available:</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Same Location Alternatives --}}
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                    <h5 class="font-semibold text-gray-800 text-sm mb-2 flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                        Same Location ({{ session('conflictClassroomLocation') }})
                                    </h5>
                                    @if(session('sameLocationAlternatives') && count(session('sameLocationAlternatives')) > 0)
                                        <ul class="space-y-2">
                                            @foreach(session('sameLocationAlternatives') as $alt)
                                                <li class="flex justify-between items-center text-xs text-gray-600 bg-gray-50/50 p-3 rounded-2xl hover:bg-white hover:shadow-sm transition border border-transparent hover:border-gray-100">
                                                    <span><strong>{{ $alt['name'] }}</strong> (Seats: {{ $alt['capacity'] }})</span>
                                                    <button type="button"
                                                        @click="
                                                            classroomId = '{{ $alt['id'] }}';
                                                            classroomName = '{{ $alt['name'] }}';
                                                            classroomLocation = '{{ $alt['location'] }}';
                                                            classroomCapacity = '{{ $alt['capacity'] }}';
                                                            showModal = true;
                                                        "
                                                        class="px-3 py-1.5 bg-limu-blue text-white rounded-full font-medium hover:bg-limu-blue-light transition shadow-sm hover:shadow">
                                                        Book
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-xs text-gray-500 italic">No alternative classrooms found in this location.</p>
                                    @endif
                                </div>

                                {{-- Similar Capacity Alternatives --}}
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-red-100">
                                    <h5 class="font-semibold text-gray-800 text-sm mb-2 flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                        Similar Capacity (Seats: {{ session('conflictClassroomCapacity') }})
                                    </h5>
                                    @if(session('similarCapacityAlternatives') && count(session('similarCapacityAlternatives')) > 0)
                                        <ul class="space-y-2">
                                            @foreach(session('similarCapacityAlternatives') as $alt)
                                                <li class="flex justify-between items-center text-xs text-gray-600 bg-gray-50/50 p-3 rounded-2xl hover:bg-white hover:shadow-sm transition border border-transparent hover:border-gray-100">
                                                    <span><strong>{{ $alt['name'] }}</strong> ({{ $alt['location'] }}, Seats: {{ $alt['capacity'] }})</span>
                                                    <button type="button"
                                                        @click="
                                                            classroomId = '{{ $alt['id'] }}';
                                                            classroomName = '{{ $alt['name'] }}';
                                                            classroomLocation = '{{ $alt['location'] }}';
                                                            classroomCapacity = '{{ $alt['capacity'] }}';
                                                            showModal = true;
                                                        "
                                                        class="px-3 py-1.5 bg-limu-blue text-white rounded-full font-medium hover:bg-limu-blue-light transition shadow-sm hover:shadow">
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

        {{-- Search & Filter Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
            <form method="GET" action="{{ route('classrooms.index') }}">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            {{ __('Search & Filter') }}
                        </h3>
                        <a href="{{ route('classrooms.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Clear Filters') }}
                        </a>
                    </div>

                    {{-- Row 1: Name Search + Date, Time, Capacity --}}
                    <div class="mb-4">
                        <label for="filter_name" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Classroom Name') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" name="q" id="filter_name" value="{{ $search ?? '' }}"
                                placeholder="e.g. Lab 101, Lecture Hall..."
                                class="w-full pl-10 pr-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="filter_date" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Date') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="date" name="date" id="filter_date" value="{{ $date }}"
                                    @change="date = $el.value"
                                    class="w-full pl-10 pr-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition">
                            </div>
                        </div>

                        <div>
                            <label for="filter_time" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Time') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </span>
                                <input type="time" name="start_time" id="filter_time" value="{{ $startTime }}"
                                    @change="startTime = $el.value"
                                    class="w-full pl-10 pr-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition">
                            </div>
                            <input type="hidden" name="end_time" value="{{ $endTime ?? '' }}" id="filter_end_time">
                        </div>

                        <div>
                            <label for="filter_capacity" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Capacity') }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <select name="capacity" id="filter_capacity"
                                    class="w-full pl-10 pr-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition appearance-none">
                                    <option value="">{{ __('Any Capacity') }}</option>
                                    <option value="20" {{ ($capacity ?? '') == '20' ? 'selected' : '' }}>20+ seats</option>
                                    <option value="30" {{ ($capacity ?? '') == '30' ? 'selected' : '' }}>30+ seats</option>
                                    <option value="40" {{ ($capacity ?? '') == '40' ? 'selected' : '' }}>40+ seats</option>
                                    <option value="50" {{ ($capacity ?? '') == '50' ? 'selected' : '' }}>50+ seats</option>
                                    <option value="60" {{ ($capacity ?? '') == '60' ? 'selected' : '' }}>60+ seats</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Refine by --}}
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">{{ __('Refine by') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="filter_location" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Location (University Wing)') }}</label>
                                <select name="location" id="filter_location"
                                    class="w-full px-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition appearance-none">
                                    <option value="">{{ __('All Locations') }}</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc }}" {{ ($location ?? '') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="filter_size" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Size') }}</label>
                                <select name="size" id="filter_size"
                                    class="w-full px-3 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition appearance-none">
                                    <option value="">{{ __('All Sizes') }}</option>
                                    <option value="small">{{ __('Small (1-30)') }}</option>
                                    <option value="medium">{{ __('Medium (31-50)') }}</option>
                                    <option value="large">{{ __('Large (51+)') }}</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 cursor-pointer select-none">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    {{ __('Show my favourites only') }}
                                </label>
                                <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors focus:outline-none"
                                    x-data="{ on: false }" @click="on = !on" :class="on ? 'bg-limu-blue' : 'bg-gray-200 dark:bg-gray-700'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform" :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden submit --}}
                    <button type="submit" class="hidden">Search</button>
                </div>

                {{-- Auto-submit on change --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.querySelector('form[action*="classrooms"]');
                        if (form) {
                            form.querySelectorAll('select, input[type="date"], input[type="time"]').forEach(el => {
                                el.addEventListener('change', () => {
                                    // Auto-set end_time to 1 hour after start_time if not set
                                    const startEl = document.getElementById('filter_time');
                                    const endEl = document.getElementById('filter_end_time');
                                    if (startEl.value && !endEl.value) {
                                        const [h, m] = startEl.value.split(':').map(Number);
                                        endEl.value = String(Math.min(h + 1, 23)).padStart(2, '0') + ':' + String(m).padStart(2, '0');
                                    }
                                    form.submit();
                                });
                            });
                        }
                    });
                </script>
            </form>
        </div>

        {{-- Available Classrooms Header --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ __('Available Classrooms') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $classrooms->count() }} {{ __('rooms found') }}</p>
                </div>
            </div>

            {{-- Grid / List Toggle --}}
            <div class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm">
                <button @click="viewMode = 'grid'"
                    :class="viewMode === 'grid' ? 'bg-limu-blue text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    {{ __('Grid') }}
                </button>
                <button @click="viewMode = 'list'"
                    :class="viewMode === 'list' ? 'bg-limu-blue text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold transition-all border-l border-gray-200 dark:border-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    {{ __('List') }}
                </button>
            </div>
        </div>

        {{-- Classroom Grid --}}
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($classrooms as $classroom)
                <a href="{{ route('classrooms.show', $classroom) }}"
                   class="group bg-white dark:bg-gray-800 rounded-[20px] border border-gray-100/50 dark:border-gray-700/50 p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(123,63,228,0.15)] hover:border-limu-blue-light/30 transition-all duration-300 flex items-start gap-4 cursor-pointer relative overflow-hidden">
                    
                    {{-- Decorative left accent bar for active hover --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-accent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    {{-- Calendar Icon (Avatar style) --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-sm {{ $classroom->status === 'free' ? 'bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400' : 'bg-red-50 dark:bg-red-950/20 text-red-500 dark:text-red-400' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-limu-blue dark:group-hover:text-limu-blue-light transition-colors">{{ $classroom->name }}</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $classroom->location }}</p>
                            </div>
                            
                            {{-- Favourite Toggle (Clicking should not trigger the card link) --}}
                            @php
                                $isFavourited = Auth::user()->favourites->contains($classroom->id);
                            @endphp
                            <object>
                                <form action="{{ $isFavourited ? route('favourites.destroy', $classroom) : route('favourites.store', $classroom) }}" method="POST" class="inline-block z-10">
                                    @csrf
                                    @if($isFavourited) @method('DELETE') @endif
                                    <button type="submit" class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ $isFavourited ? 'text-red-500' : 'text-gray-300 hover:text-red-400' }}" title="{{ $isFavourited ? 'Remove from Favourites' : 'Add to Favourites' }}">
                                        <svg class="w-5 h-5" fill="{{ $isFavourited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </object>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Capacity: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $classroom->capacity }}</span>
                            </p>
                            <span class="text-xs font-bold {{ $classroom->status === 'free' ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                                {{ ucfirst($classroom->status) }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">{{ __('No classrooms found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your filters to find matching classrooms.') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Classroom List View --}}
        <div x-show="viewMode === 'list'" x-cloak class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-5 py-3">{{ __('Classroom') }}</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-5 py-3">{{ __('Location') }}</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-5 py-3">{{ __('Capacity') }}</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-5 py-3">{{ __('Status') }}</th>
                        <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-5 py-3">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($classrooms as $classroom)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $classroom->name }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $classroom->location }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $classroom->capacity }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $classroom->status === 'free' ? 'bg-green-50 dark:bg-green-950/20 text-green-700 dark:text-green-400' : 'bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $classroom->status === 'free' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($classroom->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('classrooms.show', $classroom) }}" class="text-xs text-limu-blue dark:text-limu-blue-light hover:text-limu-blue-light font-bold transition">
                                    View &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No classrooms found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Booking Modal --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 transform transition-all duration-300"
                @click.away="showModal = false">

                <div class="px-6 py-4 bg-limu-blue dark:bg-gray-900 text-white flex justify-between items-center border-b border-transparent dark:border-gray-700">
                    <div>
                        <h3 class="font-bold text-lg">Book Classroom</h3>
                        <p class="text-xs text-blue-200 dark:text-blue-300">Confirm booking details below</p>
                    </div>
                    <button type="button" @click="showModal = false" class="text-white hover:text-blue-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="classroom_id" :value="classroomId">
                    <input type="hidden" name="search_capacity" value="{{ $capacity ?? '' }}">
                    <input type="hidden" name="search_location" value="{{ $location ?? '' }}">

                    <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 p-4 rounded-xl flex justify-between items-center">
                        <div>
                            <h4 class="font-extrabold text-limu-blue dark:text-limu-blue-light text-sm" x-text="classroomName"></h4>
                            <p class="text-xs text-blue-600 dark:text-blue-400" x-text="classroomLocation"></p>
                        </div>
                        <span class="px-2.5 py-1 bg-white dark:bg-gray-900 text-limu-blue dark:text-white text-xs font-bold rounded-lg shadow-sm border border-blue-100 dark:border-gray-700">
                            Seats: <span x-text="classroomCapacity"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Date</label>
                            <input type="date" name="date" x-model="date" required
                                class="w-full rounded-full border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Start Time</label>
                            <input type="time" name="start_time" x-model="startTime" required
                                class="w-full rounded-full border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">End Time</label>
                            <input type="time" name="end_time" x-model="endTime" required
                                class="w-full rounded-full border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Purpose / Course Name</label>
                        <input type="text" name="purpose" x-model="purpose" placeholder="e.g. CS 101 Lecture, Meeting" required
                            class="w-full rounded-full border-gray-200 dark:border-gray-700 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:border-limu-light focus:ring focus:ring-limu-blue/20 transition px-4 py-2">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-full text-sm transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-md hover:shadow-[0_4px_14px_0_rgba(123,63,228,0.39)] transition-all">
                            Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
