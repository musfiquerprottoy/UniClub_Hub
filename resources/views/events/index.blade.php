<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-8">Campus Events</h2>

            <div class="space-y-6">
                @foreach($events as $event)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 flex flex-col md:flex-row items-center gap-6 hover:border-indigo-200 transition-all">
                    <div class="flex-none w-20 h-20 bg-indigo-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg shadow-indigo-100">
                        <span class="text-xs font-black uppercase tracking-widest opacity-80">{{ \Carbon\Carbon::parse($event->start_time)->format('M') }}</span>
                        <span class="text-2xl font-black">{{ \Carbon\Carbon::parse($event->start_time)->format('d') }}</span>
                    </div>

                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-900">{{ $event->title }}</h3>
                        <p class="text-sm text-gray-500 font-bold">{{ $event->club->name }} • {{ $event->venue->name ?? 'TBA' }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="px-4 py-1 bg-green-100 text-green-700 rounded-full text-xs font-black uppercase">Confirmed</span>
                        <a href="#" class="px-6 py-3 bg-indigo-50 text-indigo-700 font-bold rounded-2xl hover:bg-indigo-100 transition">Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>