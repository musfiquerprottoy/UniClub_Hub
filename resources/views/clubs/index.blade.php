<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight">Explore Clubs</h2>
                    <p class="text-gray-500 font-medium mt-1">Join a community that matches your passion.</p>
                </div>
                
                <div class="relative w-full md:w-auto">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        🔍
                    </span>
                    <input type="text" placeholder="Search clubs..." 
                           class="pl-11 pr-4 py-3 rounded-2xl border-gray-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-80 shadow-sm transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($clubs as $club)
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-6 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
                    
                    <div class="flex items-center gap-5 mb-6">
                        {{-- LOGO SECTION: Uses the model accessor logo_url --}}
                        <div class="w-20 h-20 bg-indigo-50 rounded-3xl overflow-hidden flex items-center justify-center shrink-0 border border-indigo-100 group-hover:scale-105 transition-transform">
                            <img src="{{ $club->logo_url }}" 
                                 alt="{{ $club->name }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <div>
                            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                                {{ $club->category }}
                            </span>
                            <h3 class="text-xl font-black text-gray-900 mt-1 line-clamp-1">{{ $club->name }}</h3>
                            
                            <p class="text-sm text-gray-500 font-bold mt-0.5">
                                <span class="text-green-500">●</span> 
                                {{ $club->accepted_members_count }} {{ Str::plural('Member', $club->accepted_members_count) }}
                            </p>
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-8 min-h-[4.5rem]">
                        {{ $club->description ?? 'No description available for this club yet.' }}
                    </p>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('clubs.show', $club->id) }}" 
                           class="flex-1 text-center py-4 bg-gray-900 text-white text-sm font-black rounded-2xl hover:bg-indigo-600 transition-colors shadow-lg shadow-gray-200">
                            Visit Club
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                    <span class="text-5xl block mb-4">🏜️</span>
                    <h3 class="text-xl font-bold text-gray-900">No clubs found</h3>
                    <p class="text-gray-500">Try adjusting your search or check back later.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>