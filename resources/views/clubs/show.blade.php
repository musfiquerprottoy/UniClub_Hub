<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50 py-12 overflow-hidden relative">

        {{-- Decorative Orbs --}}
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-300/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-300/20 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 relative z-10">

            {{-- Alert Notification --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold shadow-sm flex items-center gap-3 animate-bounce">
                    <span>✅</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8">
                <a href="{{ route('clubs.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to All Clubs
                </a>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/30 rounded-[3rem] overflow-hidden shadow-[0_20px_80px_rgba(0,0,0,0.08)]">

                {{-- Header / Cover --}}
                <div class="relative h-[320px] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-700"></div>
                    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl"></div>

                    <div class="absolute bottom-[-60px] left-12">
                        <div class="relative">
                            @if($club->logo)
                                <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}"
                                     class="w-36 h-36 rounded-[2.5rem] object-cover border-4 border-white shadow-2xl bg-white">
                            @else
                                <div class="w-36 h-36 rounded-[2.5rem] border-4 border-white shadow-2xl bg-indigo-600 flex items-center justify-center text-white text-4xl font-black">
                                    {{ substr($club->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-green-500 w-8 h-8 rounded-full border-4 border-white"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-24 px-12 pb-12">

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black tracking-widest uppercase">
                                    {{ $club->category }}
                                </span>
                                <span class="text-gray-400 font-medium text-sm">
                                    Since {{ \Carbon\Carbon::parse($club->creation_date)->format('Y') }}
                                </span>
                            </div>

                            <h1 class="text-5xl font-black text-gray-900 tracking-tight leading-none">
                                {{ $club->name }}
                            </h1>

                            <p class="mt-4 text-gray-500 text-lg max-w-2xl leading-relaxed">
                                {{ $club->description }}
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-4">
                            @auth
                                {{-- Role-based Action Buttons --}}
                                @if(Auth::user()->role === 'student')
                                    @php
                                        $membership = $club->members()->where('user_id', Auth::id())->first();
                                    @endphp

                                    @if(!$membership)
                                        <a href="{{ route('clubs.join', $club->id) }}"
                                           class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl hover:scale-105 transition-all duration-300 shadow-2xl shadow-indigo-200">
                                            <span>Apply to Join Club</span>
                                            <span class="text-lg">✨</span>
                                        </a>
                                    @elseif($membership->status === 'pending')
                                        <button disabled class="px-8 py-4 bg-gray-100 text-gray-400 font-black rounded-2xl cursor-not-allowed">
                                            Pending Approval...
                                        </button>
                                    @else
                                        <form action="{{ route('clubs.leave', $club->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this club?')">
                                            @csrf @method('DELETE')
                                            <button class="px-8 py-4 bg-red-50 text-red-600 border border-red-100 font-black rounded-2xl hover:bg-red-100 transition-all">
                                                Leave Club
                                            </button>
                                        </form>
                                    @endif

                                @elseif(Auth::user()->role === 'executive')
                                    @if($club->user_id === Auth::id())
                                        <a href="{{ route('clubs.manage-members', $club->id) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-gray-800 transition">
                                            Manage Club
                                        </a>
                                    @else
                                        {{-- LOGIC: Check for Pending Request --}}
                                        @php
                                            $pendingRequest = \App\Models\ClubApplication::where('user_id', auth()->id())
                                                ->where('club_id', $club->id)
                                                ->where('status', 'pending')
                                                ->exists();
                                        @endphp

                                        @if($pendingRequest)
                                            <div class="bg-indigo-50 p-6 rounded-[2rem] border border-indigo-100 text-center w-full max-w-sm shadow-sm">
                                                <p class="text-indigo-900 font-black text-lg">⏳ Under Review</p>
                                                <p class="text-indigo-600 text-xs font-bold mt-1">Application is currently under review.</p>
                                            </div>
                                        @else
                                            {{-- Request Management Form --}}
                                            <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100 w-full max-w-sm">
                                                <h4 class="text-indigo-900 font-black mb-3 flex items-center gap-2">
                                                    <span>🛡️</span> Request Management
                                                </h4>
                                                <form action="{{ route('clubs.apply', $club->id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-[10px] uppercase tracking-widest font-black text-indigo-400 mb-1">Select Advisor</label>
                                                        <select name="advisor_id" required class="w-full bg-white border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 shadow-sm">
                                                            <option value="" disabled selected>Select an Advisor</option>
                                                            @foreach(\App\Models\User::where('role', 'advisor')->get() as $advisor)
                                                                <option value="{{ $advisor->id }}">{{ $advisor->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                                        Submit Application
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-14">

                        <div class="lg:col-span-2 space-y-8">
                            <div>
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-black text-gray-900">Events</h3>
                                    <span class="text-sm font-bold text-indigo-600">{{ $club->events->where('status', 'approved')->count() }} Live Events</span>
                                </div>

                                <div class="space-y-4">
                                    @forelse($club->events as $event)
                                        @if($event->status === 'approved' || (Auth::check() && $club->user_id === Auth::id()))
                                            <div class="group bg-gradient-to-r from-white to-gray-50 border border-gray-100 rounded-3xl p-5 hover:shadow-xl transition-all duration-300">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-5">
                                                        <div class="bg-indigo-50 group-hover:bg-indigo-600 transition-all rounded-2xl w-20 h-20 flex flex-col items-center justify-center">
                                                            <span class="text-xs font-black uppercase text-indigo-600 group-hover:text-white">
                                                                {{ \Carbon\Carbon::parse($event->event_date)->format('M') }}
                                                            </span>
                                                            <span class="text-2xl font-black text-gray-900 group-hover:text-white">
                                                                {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center gap-2">
                                                                <h4 class="text-lg font-black text-gray-900">{{ $event->title }}</h4>
                                                                @if($event->status !== 'approved')
                                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-yellow-100 text-yellow-700">
                                                                        {{ $event->status }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <p class="text-gray-500 text-sm mt-1">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('events.index') }}" class="text-indigo-600 font-black hover:text-indigo-800 transition">View →</a>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="p-10 rounded-3xl border-2 border-dashed border-gray-200 text-center bg-gray-50">
                                            <p class="text-gray-400 font-medium">No events scheduled yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Sidebar Statistics --}}
                        <div class="space-y-6">
                            <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">
                                <h4 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mb-6">Club Manager</h4>
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl font-black shadow-lg">
                                        {{ substr($club->manager->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="font-black text-gray-900">{{ $club->manager->name ?? 'Unassigned' }}</h5>
                                        <p class="text-sm text-gray-500">Executive in Charge</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2rem] p-8 text-white shadow-2xl">
                                <h4 class="uppercase tracking-[0.2em] text-xs font-black opacity-70 mb-8">Club Statistics</h4>
                                <div class="space-y-6">
                                    <div>
                                        <p class="text-5xl font-black">{{ $club->events->where('status', 'approved')->count() }}</p>
                                        <p class="uppercase text-xs tracking-widest opacity-70 mt-1">Total Live Events</p>
                                    </div>
                                    <div class="h-px bg-white/20"></div>
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl">👥</div>
                                            <div>
                                                <p class="text-5xl font-black">{{ $club->accepted_members_count }}</p>
                                                <p class="uppercase text-xs tracking-widest opacity-70 mt-1">Active Members</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>