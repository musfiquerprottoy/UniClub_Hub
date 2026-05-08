<x-app-layout>
    <div class="py-12 min-h-screen flex items-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 w-full">
            
            <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl border-4 border-white/30">
                
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-yellow-400 to-orange-500 text-white rounded-3xl mb-4 shadow-lg rotate-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Join the Squad!</h2>
                    <p class="text-indigo-700 font-extrabold text-lg mt-1">{{ $club->name }}</p>
                </div>

                <form action="{{ route('clubs.join.process', $club->id) }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-black text-gray-800 uppercase tracking-wider ml-1 mb-1">Full Name</label>
                        <input type="text" name="student_name" placeholder="Enter your name" required 
                            class="w-full border-2 border-gray-300 rounded-2xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 px-4 py-3 text-gray-900 font-semibold transition-all placeholder:text-gray-400">
                        @error('student_name')
                            <p class="text-red-600 text-sm font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-800 uppercase tracking-wider ml-1 mb-1">Student ID</label>
                        <input type="text" name="student_id_input" placeholder="e.g. 2024-01-XXXX" required 
                            class="w-full border-2 border-gray-300 rounded-2xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 px-4 py-3 text-gray-900 font-semibold transition-all placeholder:text-gray-400">
                        @error('student_id_input')
                            <p class="text-red-600 text-sm font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-black text-gray-800 uppercase tracking-wider ml-1 mb-1">Semester</label>
                            <input type="text" name="semester" placeholder="Spring '24" required 
                                class="w-full border-2 border-gray-300 rounded-2xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 px-4 py-3 text-gray-900 font-semibold transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-800 uppercase tracking-wider ml-1 mb-1">Phone</label>
                            <input type="text" name="mobile_no" placeholder="Optional" 
                                class="w-full border-2 border-gray-300 rounded-2xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 px-4 py-3 text-gray-900 font-semibold transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-800 uppercase tracking-wider ml-1 mb-1">Applying As</label>
                        <div class="relative">
                            <select name="applied_role" required 
                                class="w-full border-2 border-gray-300 rounded-2xl focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 px-4 py-3 text-gray-900 font-bold transition-all appearance-none bg-white">
                                <option value="General Member">General Member</option>
                                <option value="Executive">Executive</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                            class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-lg shadow-xl hover:bg-indigo-700 hover:-translate-y-1 active:scale-95 transition-all">
                            Send Request 🚀
                        </button>
                        <a href="{{ route('clubs.show', $club->id) }}" 
                            class="block text-center mt-4 text-sm font-black text-gray-600 hover:text-indigo-800 underline decoration-2 transition-colors">
                            Wait, take me back
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>