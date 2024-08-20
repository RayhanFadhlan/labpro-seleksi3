<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FilmApiController extends Controller
{

    public function getFilmData($film)
    {
        return [
            'id' => $film->id,
            'title' => $film->title,
            'description' => $film->description,
            'director' => $film->director,
            'release_year' => (int) $film->release_year,
            'genre' => $film->genres->pluck('name')->toArray(),
            'price' => (int) $film->price,
            'duration' => (int) $film->duration,
            'video_url' => $film->video_url,
            'cover_image_url' => $film->cover_image_url,
            'created_at' => $film->created_at->toDateTimeString(),
            'updated_at' => $film->updated_at->toDateTimeString(),
        ];
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'director' => 'required|string',
                'release_year' => 'required|integer',
                'genre' => 'required|array',
                'genre.*' => 'required|string',
                'price' => 'required|numeric',
                'duration' => 'required|integer',
                'video' => 'required|file|mimes:mp4,mov,avi,flv,mkv',
                'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg'
            ]);

            $videoPath = $request->file('video')->store('videos');
            $coverImagePath = $request->file('cover_image') ? $request->file('cover_image')->store('images') : null;


            $film = Film::create([
                'id' => (string) Str::uuid(),
                'title' => $request->title,
                'description' => $request->description,
                'director' => $request->director,
                'release_year' => $request->release_year,
                'price' => $request->price,
                'duration' => $request->duration,
                'cover_image_url' => $coverImagePath,
                'video_url' => $videoPath,
            ]);


            $genreIds = [];
            foreach ($request->genre as $genreName) {
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }

            $film->genres()->attach($genreIds);

            $filmData = $this->getFilmData($film);


            return response()->json([
                'status' => 'success',
                'message' => 'Film created successfully',
                'data' => $filmData
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    public function index(Request $request)
    {
        try {


            $query = $request->input('q');


            $films = Film::with('genres')
                ->where('title', 'like', '%' . $query . '%')
                ->orWhere('director', 'like', '%' . $query . '%')
                ->get();

            $filmsData = [];
            foreach ($films as $film) {
                $filmsData[] = $this->getFilmData($film);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Films retrieved successfully',
                'data' => $filmsData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }

    }

    public function show($id)
    {
        try {
            $film = Film::with('genres')->find($id);

            if (!$film) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Film not found',
                    'data' => null
                ], 404);
            }

            $filmData = $this->getFilmData($film);

            return response()->json([
                'status' => 'success',
                'message' => 'Film retrieved successfully',
                'data' => $filmData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $film = Film::find($id);

            if (!$film) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Film not found',
                    'data' => null
                ], 404);
            }

            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'director' => 'required|string',
                'release_year' => 'required|integer',
                'genre' => 'required|array',
                'genre.*' => 'required|string',
                'price' => 'required|numeric',
                'duration' => 'required|integer',
                'video' => 'nullable|file|mimes:mp4,mov,avi,flv,mkv',
                'cover_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg'
            ]);

            if ($request->hasFile('video')) {
                if ($film->video_url) {
                    Storage::delete($film->video_url);
                }
                $videoPath = $request->file('video')->store('videos');
            } else {
                $videoPath = $film->video_url;
            }

         
            if ($request->hasFile('cover_image')) {
                if ($film->cover_image_url) {
                    Storage::delete($film->cover_image_url);
                }
                $coverImagePath = $request->file('cover_image')->store('images');
            } else {
                $coverImagePath = $film->cover_image_url;
            }

            $film->update([
                'title' => $request->title,
                'description' => $request->description,
                'director' => $request->director,
                'release_year' => $request->release_year,
                'price' => $request->price,
                'duration' => $request->duration,
                'cover_image_url' => $coverImagePath,
                'video_url' => $videoPath,
            ]);


            $genreIds = [];
            foreach ($request->genre as $genreName) {
                $genre = Genre::firstOrCreate(['name' => $genreName]);
                $genreIds[] = $genre->id;
            }

            $film->genres()->sync($genreIds);

            $filmData = $this->getFilmData($film);

            return response()->json([
                'status' => 'success',
                'message' => 'Film updated successfully',
                'data' => $filmData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $film = Film::find($id);

            if (!$film) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Film not found',
                    'data' => null
                ], 404);
            }
            
            if ($film->video_url) {
                Storage::delete($film->video_url);
            }
            if($film->cover_image_url) {
                Storage::delete($film->cover_image_url);
            }

            $filmData = $this->getFilmData($film);

            $film->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Film deleted successfully',
                'data' => $filmData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], 400);
        }
    }

}