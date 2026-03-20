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
                    
                    <div class="mt-4">
                        <a href="{{ route('clubs.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Create a New Club
                        </a>
                    </div>

                    <h4 class="text-lg font-bold mb-2 mt-8">📅 Events Awaiting Final Sign-off</h4>
                    <div class="bg-white border rounded shadow-sm overflow-hidden">
                        @php
                            // Fetch all events that have been approved by the advisor
                            $pendingEvents = \App\Models\Event::where('status', 'approved')->get();
                        @endphp

                        @forelse($pendingEvents as $event)
                            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500">Club: {{ $event->club->name }} | Date: {{ $event->event_date }}</p>
                                </div>
                                <form action="{{ route('events.finalize', $event->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm font-bold shadow-sm transition">
                                        Final Approval
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="p-4 text-gray-500 italic">No events currently awaiting finalization.</p>
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
                            <div class="p-4 text-gray-500 italic">
                                You have no new notifications. All caught up!
                            </div>
                        @endforelse
                    </div>

                @else
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <p class="text-green-700 font-semibold">🎓 Student Access</p>
                        <p class="text-sm text-green-600">Welcome to the Club Hub! Find your community below.</p>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 rounded border border-gray-200">
                        <p class="text-gray-600">The list of available clubs will appear here soon...</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>