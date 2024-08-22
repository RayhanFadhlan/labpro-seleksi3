@extends('layouts.app')

@section('content')
<div class="container mx-auto p-8 flex flex-col max-w-6xl">
    @include('components.film-detail-card', [
    'film' => $film
    ])
    <div class="pt-8">
        <div class="p-8 bg-gray-800 rounded-lg">
            <video controls class="w-full h-auto">
                <source src={{$film->video_url}} type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

</div>
@endsection