<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Page Header -->
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Classroom') }}: {{ $classroom->name }}
                </h2>
                <a href="{{ route('admin.classrooms.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold transition">&larr; {{ __('Back to Directory') }}</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-150 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-indigo-50/20 border-b border-gray-150">
                    <h3 class="text-lg font-bold text-gray-900">{{ __('Room Specifications') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('Modify details to update this room in the availability search registry.') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.classrooms.update', $classroom) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Room Name -->
                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-bold text-gray-700">{{ __('Room Name / Number') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $classroom->name) }}" required
                            class="w-full rounded-lg border-gray-250 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="space-y-1">
                        <label for="location" class="block text-sm font-bold text-gray-700">{{ __('Building / Campus Location') }}</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $classroom->location) }}" required
                            class="w-full rounded-lg border-gray-250 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Capacity -->
                        <div class="space-y-1">
                            <label for="capacity" class="block text-sm font-bold text-gray-700">{{ __('Seating Capacity') }}</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $classroom->capacity) }}" min="1" required
                                class="w-full rounded-lg border-gray-250 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                            @error('capacity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center pt-6">
                            <label for="is_active" class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $classroom->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ms-2 text-sm font-bold text-gray-700">{{ __('Make room active and bookable') }}</span>
                            </label>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Equipment Comma-separated -->
                    <div class="space-y-1">
                        <label for="equipment" class="block text-sm font-bold text-gray-700">{{ __('Equipment & Facilities (comma-separated)') }}</label>
                        <input type="text" name="equipment" id="equipment" value="{{ old('equipment', $equipmentString) }}" placeholder="{{ __('e.g. projector, whiteboard, smartboard') }}"
                            class="w-full rounded-lg border-gray-250 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm transition">
                        <p class="text-xs text-gray-400">{{ __('Separate features with commas to automatically index them as tags.') }}</p>
                        @error('equipment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-150">
                        <a href="{{ route('admin.classrooms.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm shadow-md hover:shadow-lg transition">
                            {{ __('Update Classroom') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
