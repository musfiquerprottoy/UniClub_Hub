<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tight">Campus Events</h2>
                    <p class="text-gray-500 font-medium mt-1">Discover what's happening across all clubs.</p>
                </div>
                
                {{-- Only Executives can create events --}}
                @if(Auth::user()->role === 'executive')
                    <a href="{{ route('events.create') }}" class="px-6 py-3 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        + Propose New Event
                    </a>
                @endif
            </div>

            <div class="space-y-12">
                
                {{-- 1. STUDENT'S JOINED CLUBS SECTION --}}
                @if(Auth::user()->role === 'student')
                    @php 
                        $joinedIds = Auth::user()->memberships()->where('status', 'accepted')->pluck('club_id');
                        $myClubEvents = $events->whereIn('club_id', $joinedIds)->where('status', 'approved');
                    @endphp

                    @if($myClubEvents->count() > 0)
                        <section>
                            <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-8 h-px bg-gray-200"></span> Your Club Events
                            </h3>
                            <div class="grid gap-4">
                                @foreach($myClubEvents as $event)
                                    @include('events.partials.event-row', ['event' => $event])
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endif

                {{-- 2. ALL OTHER APPROVED EVENTS --}}
                <section>
                    <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-8 h-px bg-gray-200"></span> 
                        {{ Auth::user()->role === 'student' ? 'Other Club Events' : 'All Approved Events' }}
                    </h3>
                    
                    <div class="grid gap-4">
                        @php 
                            $displayEvents = Auth::user()->role === 'student' 
                                ? $events->whereNotIn('club_id', $joinedIds)->where('status', 'approved')
                                : $events->where('status', 'approved');
                        @endphp

                        @forelse($displayEvents as $event)
                            @include('events.partials.event-row', ['event' => $event])
                        @empty
                            <div class="bg-white rounded-[2rem] p-12 text-center border-2 border-dashed border-gray-200">
                                <p class="text-gray-400 font-bold">No upcoming events found.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>