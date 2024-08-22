<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Transaction;

use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
class FilmController extends Controller
{


    public function browse(Request $request)
    {
        Log::info('Endpoint accessed: browse');
        try {
            $query = $request->input('q');

            $perPage = 8;
            $currentPage = $request->input('page', 1);
            
            $films = Film::with('genres')
                ->where('title', 'like', '%' . $query . '%')
                ->orWhere('director', 'like', '%' . $query . '%')
                ->paginate($perPage, ['*'], 'page', $currentPage);


            

            return view('browse', ['films' => $films], ['pageTitle' => 'Browse Films']);
        } catch (\Exception $e) {
            return view('browse')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function hasPurchased($userId, $filmId)
    {
        Log::info('Endpoint accessed: hasPurchased');
        return Transaction::where('user_id', $userId)->where('film_id', $filmId)->exists();
    }

    public function show(Request $request, Film $film)
    {
        Log::info('Endpoint accessed: show');
        try {
            $token = $request->cookie('auth_token');
            if (!$token) {
                return view('film-detail', ['film' => $film]);
            }
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userId = $decoded->sub;
            if ($this->hasPurchased($userId, $film->id)) {
                return view('film-watch', ['film' => $film]);
            }

            return view('film-detail', ['film' => $film]);
        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }

    }

    public function buy(Request $request, Film $film)
    {
        Log::info('Endpoint accessed: buy');
        try {


            $userId = $request->input('user_id');

            if (Transaction::where('user_id', $userId)->where('film_id', $film->id)->exists()) {
                return redirect()->route('error', ['message' => 'Film already purchased']);
            }

            $user = User::find($userId);
            if ($film->price > $user->balance) {
                return back()->with(['error' => 'Insufficient balance']);
            }
            $user->balance = $user->balance - $film->price;
            $user->save();

            Transaction::create([
                'user_id' => $userId,
                'film_id' => $film->id,
            ]);

            return redirect()->route('browse')->with('success', 'Film purchased successfully');

        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }

    }
    public function watch(Request $request, Film $film)
    {
        Log::info('Endpoint accessed: watch');
        try {
            $userId = $request->input('user_id');
            if (!Transaction::where('user_id', $userId)->where('film_id', $film->id)->exists()) {
                return redirect()->route('error', ['message' => 'Film not purchased']);
            }
            return view('film-watch', ['film' => $film]);
        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }

    }
    public function dashboard(Request $request)
    {
        Log::info('Endpoint accessed: dashboard');
        try {
            $userId = $request->input('user_id');

            $transactions = Transaction::where('user_id', $userId)->get();

            $filmIds = $transactions->pluck('film_id');

            $perPage = 8;
            $currentPage = $request->input('page', 1);

            $films = Film::whereIn('id', $filmIds)
            ->paginate($perPage, ['*'], 'page', $currentPage);

            

            return view('browse', ['films' => $films, 'pageTitle' => 'Your Purchased Films']);
        } catch (\Exception $e) {
            return redirect()->route('error', ['message' => $e->getMessage()]);
        }
    }

    public function pollFilms(Request $request)
    {
        try {
            $lastCheckTime = $request->input('last_check_time');
            $newFilms = Film::where('updated_at', '>', $lastCheckTime)->get();

            if ($newFilms->isEmpty()) {
                return response()->json(['status' => 'no_new_films']);
            }   

            $filmCards = '';
            foreach ($newFilms as $film) {
                $filmCards .= view('components.film-card', [
                    'coverImageUrl' => $film->cover_image_url,
                    'title' => $film->title,
                    'description' => $film->description,
                    'director' => $film->director,
                    'genres' => $film->genres->pluck('name')->join(', '),
                    'price' => $film->price,
                ])->render();
            }
            return response()->json([
                'status' => 'new_films',
                'filmCards' => $filmCards,
            ]);
        } catch (\Exception $e) {
            \Log::error('Polling error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
