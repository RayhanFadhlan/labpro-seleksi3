@extends('layouts.app')

@section('title', 'Browse Films')

@section('content')
<div class="container mx-auto py-8 text-white">
    <h2 class="text-4xl font-bold mb-8 text-center">Explore Our Collection</h2>

    <!-- Search Bar -->
    <div class="flex justify-center mb-8">
        <form method="GET" action="{{ route('browse') }}" class="w-full max-w-md">
            <div class="flex items-center">
                <input type="text" name="q" placeholder="Search for a film..."
                    class="w-full px-4 py-2 rounded-l-md bg-gray-800 text-white border-none focus:ring-2 focus:ring-blue-500"
                    value="{{ request()->input('q') }}">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Film Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($films as $film)
            <a href="{{ route('films.show', $film->id) }}">
                @include('components.film-card', [
                'coverImageUrl' => $film->cover_image_url,
                'title' => $film->title,
                'description' => $film->description,
                'director' => $film->director,
                'genres' => $film->genres->pluck('name')->join(', '),
                'price' => $film->price,
            ])
                </a>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="mt-8 flex justify-center w-full flex-col">
        {{ $films->links() }}
    </div>
</div>
@endsection