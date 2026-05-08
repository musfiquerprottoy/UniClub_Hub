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

            {{-- 2. ADVISOR SECTION: Review Proposals --}}
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
                                <p class="text-xs text-indigo-600 font-bold mb-4 uppercase tracking-widest">{{ $event->club->name }}</p>
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
                            <p class="text-gray-400 font-bold italic">No events waiting for advisor review.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            {{-- 3. ADMIN SECTION: Final Decision (Edit, Approve, Reject) --}}
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
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">{{ $event->club->name }}</span>
                                </div>
                                
                                <div class="space-y-2 mt-auto">
                                    {{-- 1. APPROVE --}}
                                    <form action="{{ route('events.approve', $event->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-black text-[10px] tracking-widest hover:bg-emerald-700 transition uppercase">
                                            APPROVE & GO LIVE
                                        </button>
                                    </form>

                                    <div class="flex gap-2">
                                        {{-- 2. EDIT --}}
                                        <a href="{{ route('events.edit', $event->id) }}" class="flex-1 py-3 bg-gray-100 text-gray-700 text-center rounded-xl font-black text-[10px] tracking-widest hover:bg-gray-200 transition uppercase">
                                            EDIT
                                        </a>

                                        {{-- 3. REJECT --}}
                                        <form action="{{ route('events.reject', $event->id) }}" method="POST" class="flex-1">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-full py-3 bg-white text-red-500 border border-red-100 rounded-xl font-black text-[10px] tracking-widest hover:bg-red-50 transition uppercase">
                                                REJECT
                                            </button>
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

            {{-- 4. EXECUTIVE SECTION: Managed Clubs --}}
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
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($myClubs as $club)
                            <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-gray-100 flex justify-between items-center">
                                <div>
                                    <h4 class="text-xl font-black text-gray-900">{{ $club->name }}</h4>
                                    <p class="text-gray-400 text-xs font-bold uppercase mt-1">Executive Access</p>
                                </div>
                                <a href="{{ route('clubs.manage-members', $club->id) }}" class="px-5 py-3 bg-purple-50 text-purple-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-purple-100">Manage</a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <hr class="border-gray-200 my-12">

            {{-- 5. GLOBAL UPCOMING EVENTS (Visible to EVERYONE) --}}
            <section class="space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-500 rounded-xl text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
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
                            {{-- Event Image --}}
                            <div class="h-44 bg-gray-50 relative">
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $event->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-indigo-50 text-indigo-200 italic font-black uppercase">
                                        {{ substr($event->title, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-full shadow-sm text-[9px] font-black text-indigo-600 uppercase tracking-widest">
                                    {{ $event->club->name }}
                                </div>
                            </div>

                            {{-- Event Details --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <h4 class="text-lg font-black text-gray-900 group-hover:text-indigo-600 transition">{{ $event->title }}</h4>
                                
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span class="mr-2 text-indigo-500">📅</span> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                    </div>
                                    
                                    @if($event->start_time)
                                    <div class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span class="mr-2 text-indigo-500">⏰</span> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
                                    </div>
                                    @endif

                                    <div class="flex items-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <span class="mr-2 text-indigo-500">📍</span> {{ $event->venue ? $event->venue->name : $event->location }}
                                    </div>
                                </div>

                                <p class="mt-4 text-xs text-gray-400 font-medium italic line-clamp-2">
                                    "{{ $event->description }}"
                                </p>

                                <div class="mt-auto pt-6">
                                    <button class="w-full py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition shadow-lg">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 bg-white rounded-[4rem] border-2 border-dashed border-gray-100 text-center">
                            <div class="text-4xl mb-3 text-gray-200">🎈</div>
                            <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Check back later for new events!</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</x-app-layout>