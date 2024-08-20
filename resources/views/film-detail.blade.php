@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8">
    <div
        class="max-w-6xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden flex flex-col lg:flex-row">
        <!-- Cover Image -->
        <div class="w-full lg:w-1/3">
            <img src="{{ asset($film->cover_image_url) }}" alt="{{ $film->title }}"
                class="w-full h-full object-cover lg:h-[500px]">
        </div>

        <!-- Film Details -->
        <div class="w-full lg:w-2/3 p-8 flex flex-col justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">{{ $film->title }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mt-4">{{ $film->description }}</p>

                <div class="mt-6">
                    <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Director: </span>
                    <span class="text-lg text-gray-600 dark:text-gray-300">{{ $film->director }}</span>
                </div>

                <div class="mt-4">
                    <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Genres: </span>
                    <span
                        class="text-lg text-gray-600 dark:text-gray-300">{{ $film->genres->pluck('name')->join(', ') }}</span>
                </div>

                <div class="mt-4">
                    <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Release Year: </span>
                    <span class="text-lg text-gray-600 dark:text-gray-300">{{ $film->release_year }}</span>
                </div>

                <div class="mt-4">
                    <span class="font-semibold text-lg text-gray-700 dark:text-gray-300">Duration: </span>
                    <span class="text-lg text-gray-600 dark:text-gray-300">{{ $film->duration }} minutes</span>
                </div>
            </div>

            <!-- Buy Section -->
            <div class="mt-8 lg:mt-6">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-3xl text-gray-700 dark:text-gray-300">Price:
                        Rp{{ $film->price }}</span>
                    <button
                        class="px-8 py-3 bg-blue-600 text-white font-bold text-lg rounded-lg hover:bg-blue-700 transition">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection