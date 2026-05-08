<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-black mb-6">My Clubs</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($joinedClubs as $club)
                            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                                <img src="{{ asset('storage/'.$club->logo) }}" class="w-12 h-12 rounded-xl mb-4">
                                <h4 class="font-bold text-lg">{{ $club->name }}</h4>
                                <a href="{{ route('clubs.show', $club->id) }}" class="text-indigo-600 text-sm font-bold mt-2 inline-block">View Club</a>
                            </div>
                        @empty
                            <p class="text-gray-400">You haven't joined any clubs yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-indigo-100/50">
                    <h3 class="text-xl font-black mb-6 flex items-center">
                        <span class="mr-2">📅</span> Upcoming Events
                    </h3>
                    <div class="space-y-6">
                        @forelse($upcomingEvents as $event)
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <p class="font-bold text-gray-900">{{ $event->title }}</p>
                                <p class="text-xs text-gray-500">{{ $event->event_date->format('M d, Y') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No events scheduled.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>