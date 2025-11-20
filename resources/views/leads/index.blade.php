@extends('layouts.app')

@section('title', 'Leads List')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-center">Leads List</h1>

@if($leads->isEmpty())
    <p class="text-center text-gray-600">No leads found.</p>
@else
    <div class="flex justify-between items-center mb-4">
        <!-- Results per page dropdown -->
        <form method="GET" class="flex items-center space-x-2">
            <label for="per_page" class="text-gray-700">Results per page:</label>
            <select name="per_page" id="per_page" onchange="this.form.submit()"
                class="border-gray-300 rounded px-2 py-1">
                @foreach([5, 10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </form>

        <p class="text-gray-600 text-sm">Total Leads: {{ $leads->total() }}</p>
    </div>

    <div class="overflow-x-auto mb-4">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-2 px-4 text-left">#</th>
                    <th class="py-2 px-4 text-left">Full Name</th>
                    <th class="py-2 px-4 text-left">Email</th>
                    <th class="py-2 px-4 text-left">Consent</th>
                    <th class="py-2 px-4 text-left">Created At</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @foreach($leads as $index => $lead)
                    <tr class="@if($index % 2 == 0) bg-gray-100 @endif">
                        <td class="py-2 px-4">{{ $leads->firstItem() + $index }}</td>
                        <td class="py-2 px-4">{{ $lead->full_name }}</td>
                        <td class="py-2 px-4">{{ $lead->email }}</td>
                        <td class="py-2 px-4">{{ $lead->consent ? 'Yes' : 'No' }}</td>
                        <td class="py-2 px-4">{{ $lead->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-2 px-4">
                            <a href="{{ route('leads.edit', $lead->_id ?? $lead->id) }}"
                            class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition cursor-pointer">
                            Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $leads->links('pagination::tailwind') }}
    </div>
@endif
@endsection
