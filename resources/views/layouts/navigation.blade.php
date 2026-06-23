{{-- Sidebar Navigation --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : 'rtl:translate-x-full ltr:-translate-x-full'"
    class="fixed inset-y-0 start-0 z-50 w-[240px] bg-limu-blue dark:bg-gray-900 text-white flex flex-col transition-transform duration-300 ease-in-out lg:!translate-x-0 border-e border-transparent dark:border-gray-800"
>


    {{-- Navigation Links --}}
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
        @if(!auth()->user()->isAdmin())
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-sm ring-1 ring-white/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="lg:opacity-80">{{ __('Dashboard') }}</span>
            </a>

            {{-- My Bookings --}}
            <a href="{{ route('classrooms.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('classrooms.index') ? 'bg-white/20 text-white shadow-sm ring-1 ring-white/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('classrooms.index') ? 'text-white' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="lg:opacity-80">{{ __('My Bookings') }}</span>
            </a>

            {{-- My Reservations --}}
            <a href="{{ route('reservations.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('reservations.index') ? 'bg-white/20 text-white shadow-sm ring-1 ring-white/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('reservations.index') ? 'text-white' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="lg:opacity-80">{{ __('My Reservations') }}</span>
            </a>

            {{-- Favourites --}}
            <a href="{{ route('favourites.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('favourites.index') ? 'bg-white/20 text-white shadow-sm ring-1 ring-white/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('favourites.index') ? 'text-white' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span class="lg:opacity-80">{{ __('Favourites') }}</span>
            </a>

            <div class="my-4 border-t border-white/10"></div>

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('profile.edit') ? 'bg-white/20 text-white shadow-sm ring-1 ring-white/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-white/60 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="lg:opacity-80">{{ __('Profile') }}</span>
            </a>


        @endif

        @if(auth()->user()->isAdmin())
            <div class="my-4 border-t border-white/10"></div>
            <p class="px-4 text-[10px] font-semibold text-white/40 uppercase tracking-widest mb-2">{{ __('Admin') }}</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Dashboard') }}
            </a>

            <a href="{{ route('admin.classrooms.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.classrooms.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ __('Manage Classrooms') }}
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                {{ __('Manage Users') }}
            </a>
        @endif
    </nav>

    {{-- Language Switcher --}}
    <div class="px-4 py-3 border-t border-white/10" x-data="{ langOpen: false }">
        <button @click="langOpen = !langOpen" @click.away="langOpen = false"
                class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span>
                    @if(app()->getLocale() === 'en')
                        🇺🇸 {{ __('English') }}
                    @else
                        🇸🇦 {{ __('Arabic') }}
                    @endif
                </span>
            </div>
            <svg class="w-3 h-3 transition-transform duration-200" :class="langOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="langOpen" x-transition x-cloak
             class="mt-1 rounded-xl overflow-hidden bg-white/10 border border-white/10">
            <a href="{{ route('lang.switch', 'en') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors
                      {{ app()->getLocale() === 'en' ? 'bg-white/20 text-white font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                🇺🇸 {{ __('English') }}
                @if(app()->getLocale() === 'en')
                    <svg class="w-3 h-3 ms-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                @endif
            </a>
            <a href="{{ route('lang.switch', 'ar') }}"
               class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors
                      {{ app()->getLocale() === 'ar' ? 'bg-white/20 text-white font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                🇸🇦 {{ __('Arabic') }}
                @if(app()->getLocale() === 'ar')
                    <svg class="w-3 h-3 ms-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                @endif
            </a>
        </div>
    </div>

    {{-- Bottom User Info --}}
    <div class="px-4 py-4 border-t border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-white/50 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
