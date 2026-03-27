<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                
                <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                @if(Auth::user()->role === 'admin')
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
                        <p class="text-blue-700 font-semibold">🛡️ Administrator Access</p>
                        <p class="text-sm text-blue-600">You have full control over the system.</p>
                    </div>
                    
                    <div class="mt-4 mb-8">
                        <a href="{{ route('clubs.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Create a New Club
                        </a>
                    </div>

                    <h4 class="text-lg font-bold mb-2">📅 Events Awaiting Final Sign-off & Venue Allocation</h4>
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="bg-white border rounded shadow-sm overflow-hidden">
                        @php
                            // Fetch all events approved by the advisor
                            $pendingEvents = \App\Models\Event::where('status', 'approved')->get();
                            // Fetch all campus venues
                            $venues = \App\Models\Venue::all(); 
                        @endphp

                        @forelse($pendingEvents as $event)
                            <div class="p-4 border-b bg-gray-50">
                                <div class="mb-4">
                                    <p class="font-bold text-gray-800 text-lg">{{ $event->title }}</p>
                                    <p class="text-sm text-gray-600">Club: <span class="font-semibold">{{ $event->club->name }}</span> | Proposed Date: {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                                </div>
                                
                                <form action="{{ route('events.finalize', $event->id) }}" method="POST" class="flex flex-wrap items-end gap-4 bg-white p-3 rounded border shadow-sm">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="flex-1 min-w-[200px]">
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Assign Venue</label>
                                        <select name="venue_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                            <option value="">Select a room...</option>
                                            @foreach($venues as $venue)
                                                <option value="{{ $venue->id }}">{{ $venue->name }} (Cap: {{ $venue->capacity }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Start Time</label>
                                        <input type="datetime-local" name="start_time" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">End Time</label>
                                        <input type="datetime-local" name="end_time" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
                                    </div>

                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-sm font-bold shadow transition h-[38px]">
                                        Lock in Booking
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="p-6 text-gray-500 italic text-center">No events currently awaiting finalization.</p>
                        @endforelse
                    </div>

                @elseif(Auth::user()->role === 'executive')
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6 rounded">
                        <p class="text-purple-700 font-semibold">👑 Executive Access</p>
                        <p class="text-sm text-purple-600">Manage your club details, events, and membership rosters.</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Propose New Event
                        </a>
                    </div>

                @elseif(Auth::user()->role === 'advisor')
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded">
                        <p class="text-yellow-700 font-semibold">📋 Advisor Access</p>
                        <p class="text-sm text-yellow-600">Review club activities, reports, and approve requests.</p>
                    </div>

                    <h4 class="text-lg font-bold mb-2 mt-6">🔔 Your Notifications</h4>
                    <div class="bg-white border rounded shadow-sm">
                        @forelse(Auth::user()->unreadNotifications as $notification)
                            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $notification->data['title'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    <form action="{{ route('events.review', $notification->data['event_id']) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm font-bold shadow-sm transition">
                                            Approve & Forward to Admin
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-gray-500 italic text-center">
                                You have no new notifications. All caught up!
                            </div>
                        @endforelse
                    </div>

                @else
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <p class="text-green-700 font-semibold">🎓 Student Access</p>
                        <p class="text-sm text-green-600">Welcome to the Club Hub! Check out the upcoming campus events below.</p>
                    </div>

                    <h4 class="text-xl font-bold mb-4 mt-8 text-gray-800">📅 Upcoming Campus Events</h4>
                    
                    @php
                        // Fetch scheduled events that are coming up, sorted by date
                        $upcomingEvents = \App\Models\Event::where('status', 'scheduled')
                                            ->where('start_time', '>=', \Carbon\Carbon::now())
                                            ->orderBy('start_time', 'asc')
                                            ->get();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($upcomingEvents as $event)
                            <div class="bg-white border rounded-xl shadow-sm overflow-hidden hover:shadow-md transition duration-200 flex flex-col">
                                <div class="bg-indigo-600 p-4">
                                    <h5 class="text-white font-bold text-lg truncate">{{ $event->title }}</h5>
                                    <p class="text-indigo-200 text-sm font-semibold">{{ $event->club->name }}</p>
                                </div>
                                
                                <div class="p-5 flex-grow flex flex-col justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                            {{ $event->description }}
                                        </p>
                                        
                                        <div class="flex items-center text-sm text-gray-700 mb-2 font-medium">
                                            <span>🗓️</span>
                                            <span class="ml-2">{{ \Carbon\Carbon::parse($event->start_time)->format('D, M j, Y') }}</span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-700 mb-2 font-medium">
                                            <span>⏰</span>
                                            <span class="ml-2">
                                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-700 font-medium">
                                            <span>📍</span>
                                            <span class="ml-2">{{ $event->venue ? $event->venue->name : 'TBA' }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-5 pt-4 border-t border-gray-100">
                                        <button class="w-full bg-indigo-50 text-indigo-700 font-bold py-2 rounded-lg hover:bg-indigo-100 transition">
                                            RSVP / View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-12 text-center bg-gray-50 border border-dashed rounded-xl">
                                <p class="text-gray-500 text-lg">No upcoming events right now. Check back soon!</p>
                            </div>
                        @endforelse
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>