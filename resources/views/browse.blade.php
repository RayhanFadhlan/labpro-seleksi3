@extends('layouts.app')

@section('title', 'Browse Films')

@section('content')
<div class="container mx-auto py-8 text-white">
    <h2 class="text-4xl font-bold mb-8 text-center">{{ $pageTitle }}</h2>

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
    <div id="film-list" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach ($films as $film)
            <a href="{{ route('films.show', $film) }}">
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

   
    <div class="mt-8 flex justify-center w-full flex-col">
        {{ $films->links() }}
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Polling  -->
<script>
    let lastCheckTime = new Date().toISOString();
    const pollingInterval = 5000;
    function pollForNewFilms() {
        axios.get('{{ route('poll.films') }}', {
            params: {
                last_check_time: lastCheckTime
            }
        }).then(response => {
            if (response.data.status === 'new_films') {
                lastCheckTime = new Date().toISOString();
                updateFilmList(response.data.filmCards);
            }

            setTimeout(pollForNewFilms, pollingInterval);
        }).catch(error => {
            console.error('Polling error:', error);
            setTimeout(pollForNewFilms, pollingInterval);
        });
    }

    function updateFilmList(filmCards) {
        const filmListElement = document.getElementById('film-list');
        filmListElement.insertAdjacentHTML('beforeend', filmCards);
    }

    window.onload = function() {
        if (window.location.pathname !== '/dashboard') {
            pollForNewFilms();
        }
    };
</script>
@endsection
