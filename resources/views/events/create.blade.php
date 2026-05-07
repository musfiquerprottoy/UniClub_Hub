<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Propose New Event</h2>
                <p class="text-gray-500 font-medium">Fill in the details below to send your proposal for review.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-2xl shadow-indigo-100 sm:rounded-[2.5rem] border border-gray-100">
                <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Event Title')" class="text-gray-700 font-bold ml-1 mb-2" />
                        <x-text-input id="title" name="title" type="text" class="block w-full border-gray-200 focus:ring-indigo-500 rounded-2xl py-4" placeholder="e.g. Annual Tech Symposium" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="club_id" :value="__('Select Club')" class="text-gray-700 font-bold ml-1 mb-2" />
                            <select id="club_id" name="club_id" class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl py-4 shadow-sm">
                                @foreach(\App\Models\Club::where('user_id', Auth::id())->get() as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="event_date" :value="__('Proposed Date')" class="text-gray-700 font-bold ml-1 mb-2" />
                            <x-text-input id="event_date" name="event_date" type="date" class="block w-full border-gray-200 focus:ring-indigo-500 rounded-2xl py-4" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Event Description')" class="text-gray-700 font-bold ml-1 mb-2" />
                        <textarea id="description" name="description" rows="4" class="block w-full border-gray-200 focus:ring-indigo-500 rounded-2xl shadow-sm placeholder-gray-400" placeholder="Tell us what this event is about..." required></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label :value="__('Event Banner (Optional)')" class="text-gray-700 font-bold ml-1 mb-2" />
                        <div class="mt-2 flex justify-center rounded-3xl border-2 border-dashed border-gray-300 px-6 pt-5 pb-6 transition-all hover:border-indigo-400 hover:bg-indigo-50/30 group relative">
                            <div class="space-y-1 text-center">
                                <img id="image-preview" class="hidden mx-auto h-32 w-auto rounded-xl mb-4 shadow-md" />
                                
                                <div id="upload-icon" class="flex flex-col items-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 mt-2">
                                        <label for="image" class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">PNG, JPG, SVG up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-8 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-2xl font-black text-lg text-white shadow-xl hover:shadow-indigo-200 hover:scale-[1.02] active:scale-95 transition-all">
                            Submit Proposal
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('image-preview');
                const icon = document.getElementById('upload-icon');
                preview.src = reader.result;
                preview.classList.remove('hidden');
                // Hide the upload icon to focus on the image
                icon.querySelector('svg').classList.add('hidden');
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>