<div class="film-card bg-gray-900 p-4 shadow-md rounded-lg transition-transform duration-300 transform hover:scale-105">
    <div class="relative">
        <!-- Cover Image -->
        <img src="{{ $coverImageUrl }}" alt="Cover Image" class="w-full h-full  object-cover rounded-md" onerror="this.onerror=null;this.src='/samples/sample_image.png';">

        <!-- Gradient Overlay and Title -->
        <div
            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-gray-900 to-transparent p-6 h-1/3 rounded-b-md flex flex-col justify-end">
            <h2 class="text-md font-semibold text-white mb-1">{{ $title }}</h2>
            <h1 class="text-sm text-gray-300">{{$genres}}</h1>
        </div>
    </div>
</div>