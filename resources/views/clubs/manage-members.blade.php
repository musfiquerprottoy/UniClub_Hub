<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-black mb-8 text-gray-900">Manage Member Requests</h2>

            <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-bottom border-gray-100">
                        <tr>
                            <th class="p-6 text-sm font-bold text-gray-500 uppercase tracking-wider">Student Name</th>
                            <th class="p-6 text-sm font-bold text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="p-6 text-sm font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="p-6 text-sm font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingMembers as $member)
                        <tr>
                            <td class="p-6">
                                <p class="font-black text-gray-900">{{ $member->user->name }}</p>
                                <p class="text-xs text-gray-400">ID: {{ $member->student_id }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-sm font-bold text-gray-700">{{ $member->department }}</p>
                                <p class="text-xs text-gray-500">Semester: {{ $member->semester }}</p>
                            </td>
                            <td class="p-6 text-sm text-gray-600">{{ $member->mobile_no }}</td>
                            <td class="p-6 text-right space-x-2">
                                <form action="{{ route('members.update-status', $member->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="accepted">
                                    <button class="px-4 py-2 bg-green-500 text-white rounded-xl font-bold text-sm hover:bg-green-600">Accept</button>
                                </form>
                                <form action="{{ route('members.update-status', $member->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="px-4 py-2 bg-red-500 text-white rounded-xl font-bold text-sm hover:bg-red-600">Reject</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 font-medium">No pending requests.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>