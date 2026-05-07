<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl p-8 text-gray-900">

                <h3 class="text-3xl font-black mb-8 text-gray-900 tracking-tight">
                    Welcome, {{ Auth::user()->name }}!
                </h3>

                {{-- ================= ROLE LOGIC ================= --}}
                @if(Auth::user()->role === 'admin')

                    {{-- ADMIN --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-xl">
                        <p class="text-blue-700 font-bold">🛡️ Administrator Access</p>
                        <p class="text-sm text-blue-600">
                            You have full control over the system.
                        </p>
                    </div>

                    <div class="mt-4 mb-8">
                        <a href="{{ route('clubs.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                            + Create a New Club
                        </a>
                    </div>

                @elseif(Auth::user()->role === 'executive')

                    {{-- ================= EXECUTIVE ================= --}}

                    {{-- My Clubs --}}
                    <section class="mb-12">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                                <span class="text-2xl">🏢</span>
                                Your Clubs
                            </h4>
                        </div>

                        <div class="flex overflow-x-auto pb-6 gap-6 snap-x scrollbar-hide">

                            @php
                                $myClubs = \App\Models\Club::where('user_id', Auth::id())->get();
                            @endphp

                            @forelse($myClubs as $club)

                                <div class="flex-none w-72 snap-center bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group">

                                    <div class="h-32 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl mb-4 flex items-center justify-center">

                                        @if($club->logo)
                                            <img src="{{ asset('storage/' . $club->logo) }}"
                                                 class="w-20 h-20 object-cover rounded-2xl">
                                        @else
                                            <span class="text-indigo-600 font-black text-4xl">
                                                {{ substr($club->name, 0, 1) }}
                                            </span>
                                        @endif

                                    </div>

                                    <h5 class="font-bold text-gray-900 text-lg mb-1 truncate">
                                        {{ $club->name }}
                                    </h5>

                                    <span class="text-xs font-bold text-green-600 uppercase tracking-widest">
                                        ● Active
                                    </span>
                                </div>

                            @empty

                                <div class="w-full p-10 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400">
                                        No clubs found under your management.
                                    </p>
                                </div>

                            @endforelse
                        </div>
                    </section>

                    {{-- Event Proposal --}}
                    <section class="mb-12">

                        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2.5rem] p-10 shadow-xl text-white">

                            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">

                                <div class="text-center md:text-left">
                                    <h3 class="text-3xl font-black mb-2">
                                        Propose New Event ✨
                                    </h3>

                                    <p class="text-indigo-100 font-medium">
                                        Ready to host something great? Start your proposal here.
                                    </p>
                                </div>

                                <a href="{{ route('events.create') }}"
                                   class="bg-white text-indigo-700 px-10 py-4 rounded-2xl font-black shadow-lg hover:bg-indigo-50 transition-all active:scale-95">
                                    Start Proposal
                                </a>
                            </div>

                            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        </div>
                    </section>

                    {{-- Upcoming Events --}}
                    <section>

                        <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span>📅</span>
                            Upcoming Events
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            @php
                                $upEvents = \App\Models\Event::where('status', 'scheduled')
                                    ->where('start_time', '>=', now())
                                    ->orderBy('start_time', 'asc')
                                    ->take(4)
                                    ->get();
                            @endphp

                            @forelse($upEvents as $event)

                                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex items-center gap-4">

                                    <div class="w-14 h-14 bg-indigo-50 rounded-xl flex flex-col items-center justify-center text-indigo-600 font-bold">
                                        <span class="text-[10px] uppercase">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('M') }}
                                        </span>

                                        <span class="text-lg">
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('d') }}
                                        </span>
                                    </div>

                                    <div class="truncate">
                                        <h5 class="font-bold text-gray-900 truncate">
                                            {{ $event->title }}
                                        </h5>

                                        <p class="text-xs text-gray-500">
                                            {{ $event->venue ? $event->venue->name : 'Venue TBA' }}
                                        </p>
                                    </div>
                                </div>

                            @empty

                                <p class="text-gray-400 italic">
                                    No upcoming events.
                                </p>

                            @endforelse

                        </div>
                    </section>

                @elseif(Auth::user()->role === 'advisor')

                    {{-- ================= ADVISOR ================= --}}

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-8 rounded-xl">
                        <p class="text-yellow-700 font-semibold">
                            📋 Advisor Access
                        </p>
                    </div>

                    {{-- Management Requests --}}
                    <section class="mt-8">

                        <h3 class="text-2xl font-black text-gray-900 mb-6">
                            Management Requests
                        </h3>

                        @php
                            $pendingApps = \App\Models\ClubApplication::where('status', 'pending')
                                ->with(['user', 'club'])
                                ->get();
                        @endphp

                        <div class="space-y-4">

                            @forelse($pendingApps as $app)

                                <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between">

                                    <div class="flex items-center gap-4">

                                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-xl">
                                            👤
                                        </div>

                                        <div>
                                            <p class="font-bold text-gray-900">
                                                {{ $app->user->name }}
                                            </p>

                                            <p class="text-sm text-gray-500 font-medium tracking-tight">
                                                wants to manage
                                                <span class="text-indigo-600 font-bold">
                                                    {{ $app->club->name }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">

                                        <form action="{{ route('clubs.approve-application', $app->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="px-6 py-2 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('clubs.reject-application', $app->id) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button class="px-6 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition">
                                                Reject
                                            </button>
                                        </form>

                                    </div>
                                </div>

                            @empty

                                <div class="py-10 text-center bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
                                    <p class="text-gray-400">
                                        No pending management requests.
                                    </p>
                                </div>

                            @endforelse

                        </div>
                    </section>

                @else

                    {{-- ================= STUDENT ================= --}}

                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-xl">
                        <p class="text-green-700 font-semibold">
                            🎓 Student Access
                        </p>
                    </div>

                @endif
                {{-- ================= END ROLE LOGIC ================= --}}

            </div>
        </div>
    </div>
</x-app-layout>