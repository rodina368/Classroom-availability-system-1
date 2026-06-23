<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header with Add Button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Admin Classroom Management') }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ __('Add, edit, and manage all classroom rooms.') }}</p>
                </div>
                <a href="{{ route('admin.classrooms.create') }}" id="add-classroom-btn"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border border-transparent rounded-lg font-bold text-sm text-white tracking-wide transition shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add Classroom') }}
                </a>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm text-green-800 transition-all duration-300">
                    <p class="font-bold">{{ __('Success!') }}</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Classrooms Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-indigo-50/20 border-b border-gray-150">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Classrooms Directory') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('Manage classroom specifications, capacity constraints, and active statuses.') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-150">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Room Name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Location') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Capacity') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Equipment') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-150">
                            @forelse($classrooms as $classroom)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $classroom->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $classroom->location }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $classroom->capacity }} {{ __('seats') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="flex flex-wrap gap-1">
                                            @if($classroom->equipment && count($classroom->equipment) > 0)
                                                @foreach($classroom->equipment as $eq)
                                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-medium rounded border border-indigo-100">
                                                        {{ $eq }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-400 italic">{{ __('None') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($classroom->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                {{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition">
                                            {{ __('Edit') }}
                                        </a>

                                        <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom) }}" class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete classroom {{ $classroom->name }}? This will delete all its upcoming reservations.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition focus:outline-none">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 italic bg-gray-50">
                                        {{ __('No classrooms registered. Click "Add Classroom" to create one.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($classrooms->hasPages())
                    <div class="px-6 py-4 border-t border-gray-150">
                        {{ $classrooms->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
