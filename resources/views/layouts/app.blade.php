<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LIMU Classroom System') }}</title>
        <meta name="description" content="LIMU Smart Classroom Availability and Booking System">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
        </style>
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-[#F5F5F7] dark:bg-gray-900 transition-colors duration-300 relative overflow-x-hidden text-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false, theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }">
        <!-- Decorative Background Blobs -->
        <div class="fixed top-[-10%] end-[-5%] w-[40%] h-[40%] bg-gradient-accent rounded-full mix-blend-multiply filter blur-[100px] opacity-40 z-0 pointer-events-none"></div>
        <div class="fixed bottom-[-10%] start-[-10%] w-[50%] h-[50%] bg-gradient-accent rounded-full mix-blend-multiply filter blur-[120px] opacity-30 z-0 pointer-events-none"></div>

        <div class="min-h-screen flex relative z-10 p-0 lg:p-4 gap-0 lg:gap-4">
            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen lg:min-h-[calc(100vh-2rem)] lg:ms-[240px] bg-white dark:bg-gray-800 lg:rounded-[24px] shadow-2xl overflow-hidden relative transition-colors duration-300">
                <!-- Top Header Bar -->
                <header class="bg-limu-blue dark:bg-gray-900 text-white sticky top-0 z-30 lg:rounded-t-[24px] transition-colors duration-300 border-b border-transparent dark:border-gray-700">
                    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex items-center gap-4">
                            <!-- Mobile menu toggle -->
                            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white/70 hover:text-white focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-white">
                                    {{ __('Welcome,') }} {{ Auth::user()->name }}
                                </h1>
                                <p class="text-sm text-blue-200">{{ __('Find and book available classrooms easily') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 sm:gap-4">
                            <!-- Functional Search Bar -->
                            <form method="GET" action="{{ route('classrooms.index') }}" class="hidden md:block relative" x-data="{ focused: false }">
                                <input
                                    id="header-search"
                                    type="text"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder="{{ __('Search classrooms...') }}"
                                    autocomplete="off"
                                    @focus="focused = true"
                                    @blur="focused = false"
                                    class="bg-white/10 dark:bg-gray-800 border border-white/0 rounded-full py-2 ps-4 pe-10 text-sm text-white placeholder-white/50 dark:placeholder-gray-400 focus:ring-2 focus:ring-white/30 dark:focus:ring-gray-600 focus:bg-white/20 transition-all w-48 focus:w-64 outline-none"
                                >
                                <button type="submit" class="absolute end-3 top-2.5 text-white/60 hover:text-white transition-colors" title="{{ __('Search classrooms...') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </form>

                            <!-- Theme Toggle -->
                            <button type="button" @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); theme === 'dark' ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')" class="p-2 rounded-full hover:bg-white/10 dark:hover:bg-gray-800 transition-colors">
                                <svg x-show="theme === 'dark'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" x-cloak><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.32a1 1 0 011.415 0l.708.707a1 1 0 01-1.414 1.415l-.708-.708a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-2.32 4.22a1 1 0 010 1.415l-.708.708a1 1 0 01-1.414-1.414l.708-.708a1 1 0 011.415 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-2.32a1 1 0 01-1.415 0l-.708-.707a1 1 0 011.414-1.415l.708.708a1 1 0 010 1.414zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.32-4.22a1 1 0 010-1.415l.708-.708a1 1 0 011.414 1.414l-.708.708a1 1 0 01-1.415 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path></svg>
                                <svg x-show="theme === 'light'" class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20" x-cloak><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </button>

                            <!-- Profile Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gradient-accent p-0.5 shadow-sm hidden sm:block">
                                <div class="w-full h-full bg-limu-blue dark:bg-gray-800 rounded-full flex items-center justify-center text-sm font-bold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-full transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="hidden sm:inline">{{ __('Logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak x-transition.opacity></div>
    </body>
</html>
