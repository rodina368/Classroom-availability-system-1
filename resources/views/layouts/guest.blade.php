<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LIMU Classroom System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-blue-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen flex text-gray-900 dark:text-white" x-data="{ theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }">
        <!-- Two Column Layout -->
        <div class="flex-1 flex flex-col md:flex-row w-full">
            
            <!-- Left Column: Branding & Info -->
            <div class="hidden md:flex md:w-1/2 flex-col justify-between p-12 lg:p-24 relative overflow-hidden bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-gray-800">
                <!-- Background Blob -->
                <div class="absolute top-0 rtl:left-0 ltr:right-0 -mx-20 -mt-20 w-96 h-96 bg-limu-blue/10 dark:bg-limu-blue/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-lighten pointer-events-none"></div>
                
                <div class="z-10 mt-20">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 text-gray-900 dark:text-white leading-tight">
                        {{ __('Fast, Efficient and Productive') }}
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-md">
                        {{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed') }}
                    </p>
                </div>

                <div class="z-10 flex items-center justify-between w-full mt-auto pt-10">
                    <!-- Language Selector & Dark Mode -->
                    <div class="flex items-center space-x-6 rtl:space-x-reverse">
                        <!-- Language -->
                        <div x-data="{ langOpen: false }" class="relative">
                            <button @click="langOpen = !langOpen" @click.away="langOpen = false" class="flex items-center gap-2 text-sm font-medium hover:text-limu-blue dark:hover:text-limu-blue-light transition-colors">
                                @if(app()->getLocale() === 'en')
                                    <span class="text-xl">🇺🇸</span> {{ __('English') }}
                                @else
                                    <span class="text-xl">🇸🇦</span> {{ __('Arabic') }}
                                @endif
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="langOpen" x-transition class="absolute bottom-full ltr:left-0 rtl:right-0 mb-2 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden" x-cloak>
                                <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() === 'en' ? 'bg-gray-50 dark:bg-gray-700 font-semibold text-limu-blue dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
                                    🇺🇸 English
                                </a>
                                <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() === 'ar' ? 'bg-gray-50 dark:bg-gray-700 font-semibold text-limu-blue dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
                                    🇸🇦 العربية
                                </a>
                            </div>
                        </div>

                        <!-- Theme Toggle -->
                        <button type="button" @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); theme === 'dark' ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                            <svg x-show="theme === 'dark'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" x-cloak><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.32a1 1 0 011.415 0l.708.707a1 1 0 01-1.414 1.415l-.708-.708a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-2.32 4.22a1 1 0 010 1.415l-.708.708a1 1 0 01-1.414-1.414l.708-.708a1 1 0 011.415 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-2.32a1 1 0 01-1.415 0l-.708-.707a1 1 0 011.414-1.415l.708.708a1 1 0 010 1.414zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.32-4.22a1 1 0 010-1.415l.708-.708a1 1 0 011.414 1.414l-.708.708a1 1 0 01-1.415 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path></svg>
                            <svg x-show="theme === 'light'" class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20" x-cloak><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        </button>
                    </div>

                    <!-- Footer Links -->
                    <div class="flex gap-4 text-sm text-limu-blue dark:text-blue-400 font-medium">
                        <a href="#" class="hover:underline">{{ __('Terms') }}</a>
                        <a href="#" class="hover:underline">{{ __('Plans') }}</a>
                        <a href="#" class="hover:underline">{{ __('Contact Us') }}</a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Container -->
            <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-6 lg:p-12 bg-white dark:bg-gray-800 shadow-[-20px_0_40px_-10px_rgba(0,0,0,0.05)] dark:shadow-none z-20 min-h-screen md:min-h-0 relative rounded-none md:rounded-l-[40px] rtl:md:rounded-r-[40px] rtl:md:rounded-l-none">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
                
                <!-- Mobile Footer -->
                <div class="md:hidden mt-12 pt-8 border-t border-gray-100 dark:border-gray-700 w-full flex flex-col items-center gap-6">
                     <div class="flex items-center gap-6">
                        <a href="{{ route('lang.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}" class="text-sm font-medium flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            @if(app()->getLocale() === 'en')
                                <span>🇸🇦</span> Arabic
                            @else
                                <span>🇺🇸</span> English
                            @endif
                        </a>
                        <button type="button" @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); theme === 'dark' ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')" class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <svg x-show="theme === 'dark'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-cloak><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.32a1 1 0 011.415 0l.708.707a1 1 0 01-1.414 1.415l-.708-.708a1 1 0 010-1.414zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-2.32 4.22a1 1 0 010 1.415l-.708.708a1 1 0 01-1.414-1.414l.708-.708a1 1 0 011.415 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-2.32a1 1 0 01-1.415 0l-.708-.707a1 1 0 011.414-1.415l.708.708a1 1 0 010 1.414zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.32-4.22a1 1 0 010-1.415l.708-.708a1 1 0 011.414 1.414l-.708.708a1 1 0 01-1.415 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path></svg>
                            <svg x-show="theme === 'light'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-cloak><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        </button>
                    </div>
                    <div class="flex gap-4 text-xs text-limu-blue dark:text-blue-400 font-medium">
                        <a href="#">{{ __('Terms') }}</a>
                        <a href="#">{{ __('Plans') }}</a>
                        <a href="#">{{ __('Contact Us') }}</a>
                    </div>
                </div>
            </div>
            
        </div>
    </body>
</html>
