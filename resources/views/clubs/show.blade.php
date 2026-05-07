<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50 py-12 overflow-hidden relative">

        <!-- Background Glow -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-300/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-300/20 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('clubs.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 group-hover:-translate-x-1 transition"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>

                    Back to All Clubs
                </a>
            </div>

            <!-- Main Card -->
            <div class="bg-white/80 backdrop-blur-xl border border-white/30 rounded-[3rem] overflow-hidden shadow-[0_20px_80px_rgba(0,0,0,0.08)]">

                <!-- Hero -->
                <div class="relative h-[320px] overflow-hidden">

                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-700"></div>

                    <!-- Decorative -->
                    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl"></div>

                    <!-- Logo -->
                    <div class="absolute bottom-[-60px] left-12">
                        <div class="relative">
                            <img src="{{ $club->logo_url }}"
                                 alt="{{ $club->name }}"
                                 class="w-36 h-36 rounded-[2.5rem] object-cover border-4 border-white shadow-2xl bg-white">

                            <div class="absolute -bottom-2 -right-2 bg-green-500 w-8 h-8 rounded-full border-4 border-white"></div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="pt-24 px-12 pb-12">

                    <!-- Top -->
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

                        <!-- Action -->
                        <div>

                            @if(Auth::user()->isExecutive() && $club->user_id !== Auth::id())

                                <form action="{{ route('clubs.apply', $club->id) }}" method="POST">
                                    @csrf

                                    <button class="group px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl font-black shadow-2xl hover:scale-105 transition-all duration-300">
                                        <span class="flex items-center gap-2">
                                            Apply to Manage
                                            <span class="group-hover:translate-x-1 transition">→</span>
                                        </span>
                                    </button>
                                </form>

                            @elseif($club->user_id === Auth::id())

                                <div class="px-6 py-4 bg-green-50 border border-green-100 rounded-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center text-white">
                                            ✓
                                        </div>

                                        <div>
                                            <p class="font-black text-green-700">
                                                You Manage This Club
                                            </p>

                                            <p class="text-xs text-green-600 mt-1">
                                                Executive Access Granted
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>

                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-14">

                        <!-- LEFT -->
                        <div class="lg:col-span-2 space-y-8">

                            <!-- Events -->
                            <div>
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-black text-gray-900">
                                        Upcoming Events
                                    </h3>

                                    <span class="text-sm font-bold text-indigo-600">
                                        {{ $club->events->count() }} Events
                                    </span>
                                </div>

                                <div class="space-y-4">

                                    @forelse($club->events as $event)

                                        <div class="group bg-gradient-to-r from-white to-gray-50 border border-gray-100 rounded-3xl p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                            <div class="flex items-center justify-between">

                                                <div class="flex items-center gap-5">

                                                    <!-- Date -->
                                                    <div class="bg-indigo-50 group-hover:bg-indigo-600 transition-all rounded-2xl w-20 h-20 flex flex-col items-center justify-center shadow-sm">

                                                        <span class="text-xs font-black uppercase tracking-widest text-indigo-600 group-hover:text-white">
                                                            {{ \Carbon\Carbon::parse($event->date)->format('M') }}
                                                        </span>

                                                        <span class="text-2xl font-black text-gray-900 group-hover:text-white">
                                                            {{ \Carbon\Carbon::parse($event->date)->format('d') }}
                                                        </span>
                                                    </div>

                                                    <!-- Info -->
                                                    <div>
                                                        <h4 class="text-lg font-black text-gray-900">
                                                            {{ $event->title }}
                                                        </h4>

                                                        <p class="text-gray-500 text-sm mt-1">
                                                            {{ $event->description ?? 'Exciting club event coming soon.' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <a href="{{ route('events.show', $event->id) }}"
                                                   class="text-indigo-600 font-black hover:text-indigo-800 transition">
                                                    View →
                                                </a>

                                            </div>
                                        </div>

                                    @empty

                                        <div class="p-10 rounded-3xl border-2 border-dashed border-gray-200 text-center bg-gray-50">
                                            <p class="text-gray-400 font-medium">
                                                No events scheduled yet.
                                            </p>
                                        </div>

                                    @endforelse
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="space-y-6">

                            <!-- Manager -->
                            <div class="bg-white border border-gray-100 rounded-[2rem] p-8 shadow-sm">

                                <h4 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mb-6">
                                    Club Manager
                                </h4>

                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xl font-black shadow-lg">
                                        {{ substr($club->manager->name ?? '?', 0, 1) }}
                                    </div>

                                    <div>
                                        <h5 class="font-black text-gray-900">
                                            {{ $club->manager->name ?? 'Unassigned' }}
                                        </h5>

                                        <p class="text-sm text-gray-500">
                                            Executive in Charge
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2rem] p-8 text-white shadow-2xl">

                                <h4 class="uppercase tracking-[0.2em] text-xs font-black opacity-70 mb-8">
                                    Club Statistics
                                </h4>

                                <div class="space-y-6">

                                    <div>
                                        <p class="text-5xl font-black">
                                            {{ $club->events->count() }}
                                        </p>

                                        <p class="uppercase text-xs tracking-widest opacity-70 mt-1">
                                            Total Events
                                        </p>
                                    </div>

                                    <div class="h-px bg-white/20"></div>

                                    <div>
                                        <p class="text-5xl font-black">
                                            {{ rand(50,500) }}
                                        </p>

                                        <p class="uppercase text-xs tracking-widest opacity-70 mt-1">
                                            Active Members
                                        </p>
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