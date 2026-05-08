<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Join {{ $club->name }}</h2>
                <p class="mt-2 text-gray-600 font-medium">Please provide your academic details to apply for membership.</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white shadow-2xl rounded-[2.5rem] overflow-hidden">
                <form action="{{ route('clubs.join.process', $club->id) }}" method="POST" class="p-10 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2">Student ID</label>
                            <input type="text" name="student_id" required placeholder="e.g. 213010..."
                                   class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2">Mobile Number</label>
                            <input type="text" name="mobile_no" required placeholder="017..."
                                   class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2">Department</label>
                            <select name="department" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="CSE">CSE</option>
                                <option value="EEE">EEE</option>
                                <option value="BBA">BBA</option>
                                <option value="Pharmacy">Pharmacy</option>
                                <option value="English">English</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2">Current Semester</label>
                            <input type="text" name="semester" required placeholder="e.g. Fall 2026"
                                   class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2">Present Address</label>
                        <textarea name="address" rows="3" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-xl hover:scale-[1.02] transition-all duration-300">
                            Submit Membership Application
                        </button>
                        
                        <a href="{{ route('clubs.show', $club->id) }}" class="block text-center mt-4 text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                            Cancel and Go Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>