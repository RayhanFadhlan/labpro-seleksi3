@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8 flex flex-col max-w-6xl">
    @include('components.film-detail-card', [
    'film' => $film
    ])
    <div>
         <div class="mt-8 lg:mt-6 bg-gray-800 rounded-lg">
                <div class="flex items-center justify-between p-4">
                    <span class="font-semibold text-3xl text-gray-700 dark:text-gray-300">Price:
                        Rp{{ $film->price }}</span>
                    <form method="POST" action="{{ route('films.buy', $film) }}">
                        @csrf
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white font-bold text-lg rounded-lg hover:bg-blue-700 transition">
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>
    </div>
</div>
@endsection