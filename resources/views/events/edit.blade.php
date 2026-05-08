<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[3rem] overflow-hidden shadow-xl border border-gray-100">
                <div class="bg-gray-900 p-8 text-white">
                    <h2 class="text-3xl font-black italic tracking-tighter uppercase">Edit Event Proposal</h2>
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mt-1">Adjust details before final approval</p>
                </div>

                <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Club Selection --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-2">Assigned Club</label>
                            <select name="club_id" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}" {{ $event->club_id == $club->id ? 'selected' : '' }}>
                                        {{ $club->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Event Date --}}
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-2">Event Date</label>
                            <input type="date" name="event_date" value="{{ $event->event_date }}" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-2">Event Title</label>
                        <input type="text" name="title" value="{{ $event->title }}" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700" placeholder="e.g. Annual Charity Gala">
                    </div>

                    {{-- Description --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-2">Description</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">{{ $event->description }}</textarea>
                    </div>

                    {{-- Location --}}
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-gray-500 ml-2">Proposed Location</label>
                        <input type="text" name="location" value="{{ $event->location }}" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                            Update Proposal
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 text-gray-400 font-bold uppercase tracking-widest hover:text-gray-600 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>