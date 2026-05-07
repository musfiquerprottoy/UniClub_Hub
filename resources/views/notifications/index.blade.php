<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Notifications</h2>
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button class="text-sm font-bold text-indigo-600 hover:underline">Mark all as read</button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse(Auth::user()->notifications as $notification)
                <div class="p-6 rounded-3xl border {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-indigo-50/50 border-indigo-100 shadow-sm' }} transition-all">
                    <div class="flex gap-4">
                        <div class="flex-none text-2xl">
                            {{ $notification->data['icon'] ?? '🔔' }}
                        </div>
                        <div class="flex-grow">
                            <p class="font-bold text-gray-900">{{ $notification->data['title'] }}</p>
                            <p class="text-sm text-gray-600 mb-2">{{ $notification->data['message'] }}</p>
                            <span class="text-xs font-medium text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-[3rem] border border-dashed border-gray-200">
                    <p class="text-gray-400 font-medium">All caught up! No new notifications.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>