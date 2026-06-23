<x-app-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-bold text-2xl text-gray-900">{{ __('My Favourites') }}</h2>
            <p class="text-gray-500 text-sm mt-1">Classrooms you have saved for quick access.</p>
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

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($favourites as $classroom)
            <div class="group bg-white rounded-[20px] border border-gray-100/50 p-5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(123,63,228,0.15)] hover:border-limu-blue-light/30 transition-all duration-300 relative overflow-hidden flex flex-col">
                
                {{-- Decorative left accent bar for active hover --}}
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-accent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="flex items-start justify-between gap-2">
                    <a href="{{ route('classrooms.show', $classroom) }}" class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center shadow-sm bg-blue-50 text-limu-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-limu-blue transition-colors">{{ $classroom->name }}</h3>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $classroom->location }}</p>
                        </div>
                    </a>
                    
                    {{-- Unfavourite Button --}}
                    <form action="{{ route('favourites.destroy', $classroom) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 transition-colors bg-red-50 hover:bg-red-100 p-2 rounded-full">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                    <p class="text-xs text-gray-500">
                        Capacity: <span class="font-semibold text-gray-700">{{ $classroom->capacity }}</span>
                    </p>
                    <a href="{{ route('classrooms.show', $classroom) }}" class="text-xs font-bold text-limu-blue hover:underline">
                        View Schedule &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-bold text-gray-900">{{ __('No favourites found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">You haven't saved any classrooms to your favourites yet.</p>
                <a href="{{ route('classrooms.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-limu-blue hover:bg-limu-blue-light text-white font-semibold rounded-full text-sm shadow-md transition-all">{{ __('Browse Classrooms') }}</a>
            </div>
        @endforelse
    </div>
    
    @if($favourites->hasPages())
        <div class="mt-6">
            {{ $favourites->links() }}
        </div>
    @endif
</x-app-layout>
