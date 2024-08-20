<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
class FilmController extends Controller
{
    public function index()
    {
        return view('browse');
    }

    public function browse(Request $request)
    {
        try {
            $query = $request->input('q');

            $filmsQuery = Film::with('genres')
                ->where('title', 'like', '%' . $query . '%');

            $perPage = 8;
            $currentPage = $request->input('page', 1);

            $films = $filmsQuery->paginate($perPage, ['*'], 'page', $currentPage);

            return view('browse', ['films' => $films]);
        } catch (\Exception $e) {
            return view('browse')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Film $film)
    {
        return view('film-detail', ['film' => $film]);
    }
}