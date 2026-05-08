<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigation --}}
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            {{-- Title Section --}}
            <div class="mb-10">
                <h2 class="text-4xl font-black text-gray-900 tracking-tight">Establish New Club</h2>
                <p class="text-gray-500 font-medium mt-2">Set up the foundation for a new campus organization.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:rounded-[3rem] border border-gray-100">
                {{-- Form with Multipart encoding for Files --}}
                <form method="POST" action="{{ route('clubs.store') }}" enctype="multipart/form-data" class="p-8 md:p-14 space-y-10">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Left Column: Text Data --}}
                        <div class="space-y-8">
                            <div>
                                <x-input-label for="name" :value="__('Club Name')" class="text-gray-800 font-black uppercase text-xs tracking-widest ml-1 mb-3" />
                                <x-text-input id="name" name="name" type="text" class="block w-full border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-indigo-500 rounded-2xl py-4 transition-all" placeholder="e.g. Robotics Society" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="creation_date" :value="__('Official Establishment Date')" class="text-gray-800 font-black uppercase text-xs tracking-widest ml-1 mb-3" />
                                <x-text-input id="creation_date" name="creation_date" type="date" class="block w-full border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-indigo-500 rounded-2xl py-4 shadow-sm" required />
                            </div>

                            <div>
                                <x-input-label for="category" :value="__('Club Category')" class="text-gray-800 font-black uppercase text-xs tracking-widest ml-1 mb-3" />
                                <select id="category" name="category" class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl py-4 shadow-sm transition-all">
                                    <option value="academic">Academic & Technical</option>
                                    <option value="sports">Sports & Athletics</option>
                                    <option value="arts">Arts & Culture</option>
                                    <option value="social">Social Service</option>
                                </select>
                            </div>
                        </div>

                        {{-- Right Column: Logo Upload with Preview --}}
                        <div class="flex flex-col">
                            <x-input-label :value="__('Club Logo (Optional)')" class="text-gray-800 font-black uppercase text-xs tracking-widest ml-1 mb-3" />
                            
                            <div class="relative flex-grow group">
                                <label for="logo" class="flex flex-col items-center justify-center w-full h-full min-h-[250px] border-2 border-dashed border-gray-200 rounded-[2.5rem] bg-gray-50/30 cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-300 transition-all overflow-hidden relative">
                                    
                                    {{-- Image Preview Container --}}
                                    <img id="logo-preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-[2.5rem] z-0" />
                                    
                                    <div id="upload-placeholder" class="relative z-10 flex flex-col items-center text-center p-6 transition-opacity duration-300">
                                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 text-indigo-500 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-700">Click to upload logo</p>
                                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">PNG, JPG, SVG (MAX. 1MB)</p>
                                    </div>

                                    <input id="logo" name="logo" type="file" class="sr-only" accept="image/*" onchange="handleLogoPreview(event)" />
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <x-input-label for="description" :value="__('Club Vision & Description')" class="text-gray-800 font-black uppercase text-xs tracking-widest ml-1 mb-3" />
                        <textarea id="description" name="description" rows="4" class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:ring-indigo-500 rounded-3xl shadow-sm placeholder-gray-400 p-6 transition-all" placeholder="Describe the mission and goals of this club..." required></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- Submit --}}
                    <div class="pt-6">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-10 py-5 bg-gray-900 border border-transparent rounded-[2rem] font-black text-xl text-white shadow-2xl shadow-gray-200 hover:bg-indigo-600 hover:-translate-y-1 transition-all active:scale-95">
                            Establish Club
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script for instant preview --}}
    <script>
        function handleLogoPreview(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    const preview = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('upload-placeholder');
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('opacity-0'); // Fades the text out so the image shows through
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>