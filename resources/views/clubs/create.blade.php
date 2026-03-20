<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Club') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('clubs.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block font-medium text-gray-700">Club Name</label>
                        <input id="name" class="block mt-1 w-full border-gray-300 rounded-md" type="text" name="name" required autocomplete="off" />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-gray-700">Description</label>
                        <textarea id="description" class="block mt-1 w-full border-gray-300 rounded-md" name="description" rows="12" required></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save Club
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>