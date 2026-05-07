<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Explore Clubs</h2>
                <div class="relative">
                    <input type="text" placeholder="Search clubs..." class="rounded-2xl border-gray-200 text-sm focus:ring-indigo-500 w-64">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($clubs as $club)
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-6 hover:shadow-xl transition-all group">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 font-black text-xl group-hover:scale-110 transition-transform">
                            {{ substr($club->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $club->name }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $club->members_count ?? 0 }} Members</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-6">
                        {{ $club->description ?? 'Discover our activities and join our community to explore new interests.' }}
                    </p>
                    <a href="{{ route('clubs.show', $club->id) }}" class="block text-center py-3 bg-gray-50 text-indigo-700 font-bold rounded-2xl hover:bg-indigo-600 hover:text-white transition-colors">
                        View Club Details
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>