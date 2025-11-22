@extends('layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Edit Lead</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any() || session('error'))
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                {{ session('error') }}
            </ul>
        </div>
    @endif

    <form action="{{ route('leads.update', $lead->_id ?? $lead->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $lead->full_name) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $lead->email) }}" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex items-center space-x-2">
            <input id="consent" type="checkbox" name="consent" value="1"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                {{ old('consent', $lead->consent) ? 'checked' : '' }}>
            <label for="consent" class="text-sm text-gray-700">I agree to receive marketing emails.</label>
        </div>

        <div class="flex justify-between pt-2">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow transition cursor-pointer">
                Update
            </button>

            <a href="{{ route('leads.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition cursor-pointer">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
