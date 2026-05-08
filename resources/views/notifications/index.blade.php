<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex items-center justify-between mb-8 px-4 sm:px-0">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Notifications</h2>
                    <p class="text-gray-500 text-sm">Stay updated with club activities</p>
                </div>
                
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-bold text-indigo-600 hover:bg-indigo-50 transition-colors shadow-sm">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>

            {{-- Notifications List --}}
            <div class="space-y-4">
                @forelse(Auth::user()->notifications as $notification)
                <div class="p-6 rounded-3xl border transition-all duration-300 {{ $notification->read_at ? 'bg-white border-gray-100 opacity-75' : 'bg-white border-indigo-200 shadow-md ring-1 ring-indigo-50' }}">
                    <div class="flex gap-4">
                        {{-- Icon --}}
                        <div class="flex-none w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-2xl">
                            {{ $notification->data['icon'] ?? '🔔' }}
                        </div>
                        
                        {{-- Content --}}
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <p class="font-bold text-gray-900 text-lg leading-tight">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </p>
                                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded-md">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1 mb-3">{{ $notification->data['message'] }}</p>
                            
                            @if(isset($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    View Details
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 5l7 7-7 7"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-gray-200 shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                        <span class="text-2xl">✨</span>
                    </div>
                    <p class="text-gray-500 font-bold text-lg">All caught up!</p>
                    <p class="text-gray-400 text-sm">No new notifications at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>