<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Hero Header --}}
            <div class="text-center mb-16">
                <h2 class="text-5xl font-black text-gray-900 tracking-tighter uppercase italic">
                    Upcoming <span class="text-indigo-600">Events</span>
                </h2>
                <p class="mt-4 text-gray-500 font-bold uppercase tracking-widest text-sm">
                    Discover what's happening in your university community
                </p>
            </div>

            {{-- Events Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($events as $event)
                    <div class="group relative bg-white rounded-[3rem] overflow-hidden border-2 border-gray-100 hover:border-indigo-100 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                        
                        {{-- Event Image --}}
                        <div class="h-64 overflow-hidden relative">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $event->title }}">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-5xl">🗓️</div>
                            @endif
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm">
                                <p class="text-xs font-black text-indigo-600 uppercase tracking-tighter">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-8">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase rounded-full tracking-widest">
                                    {{ $event->club->name }}
                                </span>
                            </div>
                            
                            <h3 class="text-2xl font-black text-gray-900 leading-tight group-hover:text-indigo-600 transition">
                                {{ $event->title }}
                            </h3>
                            
                            <p class="mt-4 text-gray-500 text-sm font-medium line-clamp-2 italic">
                                "{{ $event->description }}"
                            </p>

                            <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between">
                                <div class="flex items-center text-gray-400 font-bold text-xs uppercase tracking-widest">
                                    <svg class="h-4 w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $event->location }}
                                </div>
                                <a href="#" class="w-10 h-10 bg-gray-900 text-white rounded-full flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                                    →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gray-50 rounded-[4rem] border-2 border-dashed border-gray-200">
                        <div class="text-6xl mb-4">🎈</div>
                        <h3 class="text-2xl font-black text-gray-900">No events found</h3>
                        <p class="text-gray-400 font-bold mt-2 uppercase tracking-widest text-xs">Check back later for new updates!</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-16">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>