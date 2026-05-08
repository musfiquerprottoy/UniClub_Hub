<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-[3rem] shadow-xl overflow-hidden border border-gray-100">
                {{-- Header --}}
                <div class="bg-indigo-600 p-8 text-white">
                    <h2 class="text-3xl font-black uppercase italic tracking-tighter">Membership Application</h2>
                    <p class="text-indigo-200 font-bold text-xs uppercase tracking-widest mt-1">
                        For Club: {{ $request->club?->name ?? 'Unknown Club' }}
                    </p>
                </div>

                <div class="p-10 space-y-8">
                    {{-- Student Basic Info --}}
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Full Name (from Form)</label>
                            {{-- Corrected variable: Displays the 'full_name' column --}}
                            <p class="text-lg font-bold text-gray-900">{{ $request->full_name }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Student ID</label>
                            <p class="text-lg font-bold text-gray-900">{{ $request->student_id }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 border-t border-gray-50 pt-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Department</label>
                            {{-- Corrected variable: Displays the 'department' column --}}
                            <p class="text-gray-700 font-bold">{{ $request->department }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Semester</label>
                            <p class="text-gray-700 font-bold">{{ $request->semester }}</p>
                        </div>
                    </div>

                    {{-- Contact & Address --}}
                    <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 block">Contact Details</label>
                        <p class="text-sm text-gray-600 font-bold mb-1">📞 {{ $request->mobile_no }}</p>
                        <p class="text-sm text-gray-500 italic mt-3 bg-white p-4 rounded-xl border border-gray-100">
                            {{-- Address usually stores the 'Applied Role' in your controller logic --}}
                            <strong>Details:</strong> {{ $request->address ?? 'No additional details' }}
                        </p>
                    </div>

                    {{-- Actions (Approve/Decline) --}}
                    <div class="pt-8 border-t border-gray-100 flex gap-4">
                        {{-- Approve Form --}}
                        <form action="{{ route('members.update-status', $request->id) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            {{-- Using a hidden input ensures 'status' is always sent --}}
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="w-full py-4 bg-emerald-500 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-600 transition shadow-lg">
                                Approve Member
                            </button>
                        </form>

                        {{-- Decline Form --}}
                        <form action="{{ route('members.update-status', $request->id) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="w-full py-4 bg-white text-red-500 border-2 border-red-50 rounded-2xl font-black uppercase tracking-widest hover:bg-red-50 transition">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="text-xs font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 transition">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>