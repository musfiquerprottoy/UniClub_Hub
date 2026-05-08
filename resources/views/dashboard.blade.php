<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 1. SUCCESS ALERT BLOCK --}}
            @if(session('success'))
                <div class="mb-6">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-[2rem] flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">✅</span>
                            <p class="font-bold text-sm">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl p-8 text-gray-900">

                {{-- Welcome Header --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 border-b border-gray-100 pb-8">
                    <div>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight">
                            Welcome, {{ Auth::user()->name }}!
                        </h3>
                        <p class="text-gray-500 font-medium">Here's what's happening in UniClub Hub today.</p>
                    </div>
                    
                    <div class="inline-flex items-center px-4 py-2 rounded-2xl font-bold text-sm 
                        @if(Auth::user()->role === 'admin') bg-blue-100 text-blue-700 
                        @elseif(Auth::user()->role === 'executive') bg-purple-100 text-purple-700
                        @elseif(Auth::user()->role === 'advisor') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        <span class="mr-2">
                            @if(Auth::user()->role === 'admin') 🛡️ Admin
                            @elseif(Auth::user()->role === 'executive') 🏢 Executive
                            @elseif(Auth::user()->role === 'advisor') 📋 Advisor
                            @else 🎓 Student @endif
                        </span>
                    </div>
                </div>

                {{-- 1. ADMIN SECTION --}}
                @if(Auth::user()->role === 'admin')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        <div class="bg-blue-600 rounded-[2rem] p-8 text-white shadow-lg shadow-blue-200">
                            <h4 class="text-xl font-bold mb-2">System Management</h4>
                            <p class="text-blue-100 mb-6 text-sm">Full administrative oversight active.</p>
                            <a href="{{ route('clubs.create') }}" class="inline-flex bg-white text-blue-600 px-6 py-3 rounded-xl font-black hover:bg-blue-50 transition">
                                + Create New Club
                            </a>
                        </div>
                        <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-lg">
                            <h4 class="text-xl font-bold mb-2">Platform Overview</h4>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div><p class="text-gray-400 text-xs uppercase font-bold">Total Clubs</p><p class="text-2xl font-black">{{ \App\Models\Club::count() }}</p></div>
                                <div><p class="text-gray-400 text-xs uppercase font-bold">Total Users</p><p class="text-2xl font-black">{{ \App\Models\User::count() }}</p></div>
                            </div>
                        </div>
                    </div>

                    <section class="mb-12">
                        <h4 class="text-xl font-black mb-6 flex items-center gap-2 text-blue-700">
                            <span>🚨</span> Events Awaiting Final Approval
                        </h4>
                        @php $pendingAdminEvents = \App\Models\Event::where('status', 'pending_admin')->latest()->get(); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($pendingAdminEvents as $event)
                                <div class="bg-white border-2 border-blue-100 rounded-[2rem] p-6 shadow-sm">
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="font-black text-lg">{{ $event->title }}</h5>
                                        <x-event-status :event="$event" />
                                    </div>
                                    <p class="text-sm text-gray-500 mb-6">Submitted by: <strong>{{ $event->club->name }}</strong></p>
                                    <form action="{{ route('events.approve', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="w-full py-3 bg-blue-600 text-white rounded-xl font-black hover:bg-blue-700 transition">Approve & Make Live</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-gray-400 italic font-medium">No events pending admin approval.</p>
                            @endforelse
                        </div>
                    </section>

                {{-- 2. ADVISOR SECTION --}}
                @elseif(Auth::user()->role === 'advisor')
                    {{-- NEW BLOCK: CLUB MANAGEMENT REQUESTS --}}
                    <section class="mb-12">
                        <h4 class="text-xl font-black mb-6 flex items-center gap-2 text-indigo-700">
                            <span>🏛️</span> Club Management Applications
                        </h4>
                        @php 
                            $myTasks = \App\Models\ClubApplication::where('advisor_id', Auth::id())
                                ->where('status', 'pending')
                                ->with(['club', 'executive'])
                                ->latest()
                                ->get(); 
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($myTasks as $app)
                                <div class="bg-white border-2 border-indigo-50 rounded-[2.5rem] p-8 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h5 class="font-black text-xl text-gray-900">{{ $app->club->name }}</h5>
                                            <p class="text-sm text-gray-500">Proposed Manager: <strong>{{ $app->executive->name }}</strong></p>
                                        </div>
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-black uppercase">Pending Approval</span>
                                    </div>
                                    <div class="flex gap-3 mt-6">
                                        <form action="{{ route('applications.approve', $app->id) }}" method="POST" class="flex-1">
                                            @csrf @method('PATCH')
                                            <button class="w-full py-3 bg-indigo-600 text-white rounded-xl font-black text-sm hover:bg-indigo-700 transition">Approve</button>
                                        </form>
                                        <form action="{{ route('applications.reject', $app->id) }}" method="POST" class="flex-1">
                                            @csrf @method('PATCH')
                                            <button class="w-full py-3 bg-red-50 text-red-600 rounded-xl font-black text-sm hover:bg-red-100 transition">Reject</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400 font-medium">No pending club management requests assigned to you.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section class="mb-12">
                        <h4 class="text-xl font-black mb-6 flex items-center gap-2 text-yellow-700">
                            <span>📋</span> Events to Review
                        </h4>
                        @php $pendingAdvisorEvents = \App\Models\Event::where('status', 'pending_advisor')->latest()->get(); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($pendingAdvisorEvents as $event)
                                <div class="bg-white border-2 border-yellow-100 rounded-[2rem] p-6 shadow-sm">
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="font-black text-lg">{{ $event->title }}</h5>
                                        <x-event-status :event="$event" />
                                    </div>
                                    <p class="text-sm text-gray-500 mb-6 font-medium">Club: {{ $event->club->name }}</p>
                                    <form action="{{ route('events.forward', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="w-full py-3 bg-yellow-500 text-white rounded-xl font-black hover:bg-yellow-600 transition">Forward to Admin</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-gray-400 italic font-medium">Event queue is empty. Well done!</p>
                            @endforelse
                        </div>
                    </section>

                {{-- 3. EXECUTIVE SECTION --}}
                @elseif(Auth::user()->role === 'executive')
                    <section class="mb-12">
                        <h4 class="text-xl font-bold text-gray-800 mb-6">🏢 Clubs You Manage</h4>
                        @php $myClubs = \App\Models\Club::where('user_id', Auth::id())->get(); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @forelse($myClubs as $club)
                                <div class="bg-white p-6 border rounded-[2rem] shadow-sm">
                                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl mb-4 flex items-center justify-center text-2xl overflow-hidden">
                                        @if($club->logo)
                                            <img src="{{ asset('storage/' . $club->logo) }}" class="w-full h-full object-cover">
                                        @else
                                            🏛️
                                        @endif
                                    </div>
                                    <h5 class="font-black text-lg truncate">{{ $club->name }}</h5>
                                    <div class="mt-4 flex flex-col gap-2">
                                        <a href="{{ route('clubs.manage-members', $club->id) }}" class="text-center py-2 bg-gray-100 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Members</a>
                                        <a href="{{ route('events.create', ['club_id' => $club->id]) }}" class="text-center py-2 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition">+ New Event</a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 italic">You aren't managing any clubs yet.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="mb-12">
                        <h4 class="text-xl font-black mb-6 flex items-center gap-2 text-purple-700">
                            <span>⏳</span> My Pending Proposals
                        </h4>
                        @php $myPending = \App\Models\Event::where('created_by', Auth::id())->where('status', '!=', 'approved')->latest()->get(); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($myPending as $event)
                                <div class="bg-white border border-gray-100 p-5 rounded-3xl shadow-sm flex justify-between items-center">
                                    <div>
                                        <p class="text-xs font-black text-indigo-500 uppercase tracking-tighter">{{ $event->club->name }}</p>
                                        <h5 class="font-bold text-gray-900">{{ $event->title }}</h5>
                                    </div>
                                    <x-event-status :event="$event" />
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400 font-medium">No active proposals at the moment.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>

                {{-- 4. STUDENT SECTION --}}
                @else
                    <section class="mb-12">
                        <h4 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-2">
                            <span>🏠</span> Events from Your Clubs
                        </h4>
                        @php 
                            $joinedIds = Auth::user()->memberships()->where('status', 'accepted')->pluck('club_id');
                            $myClubEvents = \App\Models\Event::whereIn('club_id', $joinedIds)->where('status', 'approved')->latest()->get();
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($myClubEvents as $event)
                                <div class="bg-white border-2 border-indigo-50 p-6 rounded-[2rem] shadow-sm hover:shadow-md transition">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-500">{{ $event->club->name }}</span>
                                    <h5 class="text-xl font-black mt-1 text-gray-900">{{ $event->title }}</h5>
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $event->description }}</p>
                                    <div class="mt-4 pt-4 border-t border-gray-50 text-xs font-bold text-gray-400 flex gap-4">
                                        <span>📅 {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                        <span>📍 {{ $event->location }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 italic">No upcoming events from your clubs. Try exploring!</p>
                            @endforelse
                        </div>
                    </section>

                    <section>
                        <h4 class="text-2xl font-black text-gray-900/30 mb-6">🌍 Explore Other Events</h4>
                        @php 
                            $otherEvents = \App\Models\Event::whereNotIn('club_id', $joinedIds)->where('status', 'approved')->latest()->get();
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($otherEvents as $event)
                                <div class="bg-gray-50 p-6 rounded-[2rem] border border-transparent opacity-75 hover:opacity-100 transition-all hover:bg-white hover:border-gray-100">
                                    <span class="text-[10px] font-black uppercase text-gray-400">{{ $event->club->name }}</span>
                                    <h5 class="text-xl font-black text-gray-800">{{ $event->title }}</h5>
                                    <a href="{{ route('clubs.show', $event->club_id) }}" class="inline-block mt-4 text-sm font-black text-indigo-600 hover:text-indigo-800 transition">View Club details →</a>
                                </div>
                            @empty
                                <p class="text-gray-400 italic">No other public events discovered yet.</p>
                            @endforelse
                        </div>
                    </section>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>