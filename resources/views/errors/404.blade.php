@extends('layouts.app')

@section('title', 'Lead Not Found')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-center px-6">
    <h1 class="text-8xl font-extrabold text-gray-800 mb-4">404</h1>
    <h2 class="text-3xl font-semibold text-gray-700 mb-6">Oops! Lead Not Found</h2>
    <p class="text-gray-600 mb-8 max-w-lg">
        The Lead has been removed or is temporarily unavailable.
    </p>

    <a href="{{ route('leads.index') }}" 
       class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition transform hover:-translate-y-1 hover:scale-105">
        Return to Lead list
    </a>
</div>
@endsection
