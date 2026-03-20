<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit Event Proposal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="club_id" class="block font-medium text-gray-700">Which Club is hosting this?</label>
                        <select id="club_id" name="club_id" class="block mt-1 w-full border-gray-300 rounded-md" required>
                            <option value="" disabled selected>Select a club...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="title" class="block font-medium text-gray-700">Event Title</label>
                        <input id="title" class="block mt-1 w-full border-gray-300 rounded-md" type="text" name="title" required />
                    </div>

                    <div class="mb-4">
                        <label for="event_date" class="block font-medium text-gray-700">Date</label>
                        <input id="event_date" class="block mt-1 w-full border-gray-300 rounded-md" type="date" name="event_date" required />
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block font-medium text-gray-700">Location</label>
                        <input id="location" class="block mt-1 w-full border-gray-300 rounded-md" type="text" name="location" required />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-gray-700">Proposal Details</label>
                        <textarea id="description" class="block mt-1 w-full border-gray-300 rounded-md" name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Submit Proposal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>