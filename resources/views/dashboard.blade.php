<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. HEADER SECTION --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight">
                        Welcome back, <span class="text-indigo-600">{{ Auth::user()->name }}</span>!
                    </h2>
                    <p class="text-gray-500 font-medium mt-1">University Club Portal Dashboard</p>
                </div>
                <div class="px-4 py-2 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Role:</span>
                    <span class="text-sm font-bold text-indigo-600 ml-1 capitalize">{{ Auth::user()->role }}</span>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-[2rem] font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-200 text-red-700 px-6 py-4 rounded-[2rem] font-bold shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 2. ADVISOR SECTION --}}
            @if(Auth::user()->role === 'advisor')
                <section class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-500 rounded-xl text-white">🔥</div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase italic">Pending Advisor Review</h3>
                    </div>

                    @php
                        $advisorEvents = \App\Models\Event::where('status', 'pending_advisor')->with('club')->get();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($advisorEvents as $event)
                            <div class="bg-white p-6 rounded-[2.5rem] border-2 border-orange-100 shadow-sm">
                                <h4 class="font-black text-gray-900">{{ $event->title }}</h4>
                                <p class="text-xs text-indigo-600 font-bold mb-4 uppercase tracking-widest">{{ $event->club?->name ?? 'Unknown Club' }}</p>
                                <div class="flex flex-col gap-2">
                                    <form action="{{ route('events.forward', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest">Forward to Admin →</button>
                                    </form>
                                    <form action="{{ route('events.reject', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full py-3 bg-white text-red-600 border border-red-100 rounded-xl font-black text-[10px] uppercase tracking-widest">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 font-bold italic col-span-full text-center py-4">No events waiting for advisor review.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            {{-- 3. ADMIN SECTION --}}
            @if(Auth::user()->role === 'admin')
                <section class="space-y-8">
                    <div class="bg-gray-900 rounded-[3rem] p-10 text-white flex justify-between items-center">
                        <div>
                            <h3 class="text-3xl font-black italic uppercase tracking-tighter">Admin Control Panel</h3>
                            <p class="text-gray-400 mt-2 font-bold uppercase text-xs tracking-widest">System Management & Final Approvals</p>
                        </div>
                        <a href="{{ route('clubs.create') }}" class="px-8 py-4 bg-indigo-600 rounded-2xl font-black hover:bg-indigo-500 transition uppercase text-xs">Create New Club</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-500 rounded-xl text-white">✅</div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase italic">Final Event Approvals</h3>
                    </div>

                    @php
                        $adminEvents = \App\Models\Event::where('status', 'pending_admin')->with('club')->get();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($adminEvents as $event)
                            <div class="bg-white p-6 rounded-[2.5rem] border-2 border-emerald-100 shadow-sm flex flex-col">
                                <div class="mb-6">
                                    <h4 class="font-black text-gray-900 text-lg leading-tight">{{ $event->title }}</h4>
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">{{ $event->club?->name ?? 'Unknown Club' }}</span>
                                </div>
                                <div class="space-y-2 mt-auto">
                                    <form action="{{ route('events.approve', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-black text-[10px] tracking-widest hover:bg-emerald-700 transition uppercase">APPROVE & GO LIVE</button>
                                    </form>
                                    <div class="flex gap-2">
                                        <a href="{{ route('events.edit', $event->id) }}" class="flex-1 py-3 bg-gray-100 text-gray-700 text-center rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-gray-200 transition">EDIT</a>
                                        <form action="{{ route('events.reject', $event->id) }}" method="POST" class="flex-1">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-full py-3 bg-white text-red-500 border border-red-100 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-red-50 transition">REJECT</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                                <p class="text-gray-400 font-bold italic">No events currently awaiting admin approval.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            @endif

            {{-- 4. EXECUTIVE SECTION --}}
            @if(Auth::user()->role === 'executive')
                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-600 rounded-xl text-white">✨</div>
                            <h3 class="text-2xl font-black text-gray-900 uppercase italic">Clubs You Manage</h3>
                        </div>
                        <a href="{{ route('events.create') }}" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest">
                            + Propose New Event
                        </a>
                    </div>
                    
                    @php
                        $myClubs = \App\Models\Club::where('user_id', Auth::id())->get();
                        $managedClubIds = $myClubs->pluck('id');
                        $pendingMembers = \App\Models\ClubMember::with(['user', 'club'])
                            ->whereIn('club_id', $managedClubIds)
                            ->where('status', 'pending')
                            ->get();
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($myClubs as $club)
                            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 flex justify-between items-center">
                                <div>
                                    <h4 class="text-xl font-black text-gray-900">{{ $club->name }}</h4>
                                    <p class="text-gray-400 text-xs font-bold uppercase mt-1">Executive Access</p>
                                </div>
                                <a href="{{ route('clubs.manage-members', $club->id) }}" class="px-5 py-3 bg-purple-50 text-purple-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-purple-100 transition">Manage</a>
                            </div>
                        @empty
                            <p class="text-gray-400 font-bold italic col-span-full">You are not currently managing any clubs.</p>
                        @endforelse
                    </div>

                    {{-- NEW JOIN REQUESTS INBOX --}}
                    @if($pendingMembers->count() > 0)
                        <div class="mt-12 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-yellow-500 rounded-xl text-white animate-pulse">👋</div>
                                <h3 class="text-xl font-black text-gray-900 uppercase italic">New Membership Requests</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($pendingMembers as $request)
                                    <div class="bg-white p-6 rounded-[2.5rem] border-2 border-yellow-100 shadow-sm flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-black shadow-md">
                                                {{ substr($request->full_name ?? $request->user?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                {{-- Shows full_name from the form --}}
                                                <h4 class="font-black text-gray-900">{{ $request->full_name ?? ($request->user?->name ?? 'Unknown') }}</h4>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                                    Wants to join: <span class="text-indigo-600">{{ $request->club?->name ?? 'Deleted Club' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-3">
                                            {{-- VIEW DETAILS LINK --}}
                                            <a href="{{ route('members.show-request', $request->id) }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition">
                                                View Details
                                            </a>

                                            <form action="{{ route('members.update-status', $request->id) }}" method="POST" class="flex gap-2">
                                                @csrf @method('PATCH')
                                                <button name="status" value="active" class="px-6 py-2 bg-gray-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition shadow-lg">
                                                    Approve
                                                </button>
                                                <button name="status" value="rejected" class="px-6 py-2 bg-white text-red-500 border border-red-100 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-50 transition">
                                                    Decline
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            <hr class="border-gray-200 my-12">

            {{-- 5. GLOBAL UPCOMING EVENTS --}}
            <section class="space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-500 rounded-xl text-white">🗓️</div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tight">Upcoming Events</h3>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] hover:underline">See All Activity →</a>
                </div>

                @php
                    $globalEvents = \App\Models\Event::where('status', 'approved')
                        ->with(['club', 'venue'])
                        ->orderBy('event_date', 'asc')
                        ->take(6)
                        ->get();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($globalEvents as $event)
                        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-xl transition-all duration-300">
                            <div class="h-44 bg-gray-50 relative">
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-indigo-50 text-indigo-200 italic font-black uppercase">
                                        {{ substr($event->title, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-full shadow-sm text-[9px] font-black text-indigo-600 uppercase tracking-widest">
                                    {{ $event->club?->name ?? 'Unknown' }}
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h4 class="text-lg font-black text-gray-900 group-hover:text-indigo-600 transition">{{ $event->title }}</h4>
                                <div class="mt-4 space-y-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                    <div class="flex items-center gap-2"><span class="text-indigo-500">📅</span> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</div>
                                    <div class="flex items-center gap-2"><span class="text-indigo-500">📍</span> {{ $event->venue ? $event->venue->name : $event->location }}</div>
                                </div>
                                <div class="mt-auto pt-6">
                                    <button class="w-full py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition shadow-lg">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 bg-white rounded-[4rem] border-2 border-dashed border-gray-100 text-center">
                            <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No events scheduled yet!</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>