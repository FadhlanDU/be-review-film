<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verifiedAccount']);
    }

    public function storeupdate(Request $request) {
        $user = auth()->user();

        $request->validate([
            'critique' => 'required',
            'rating' => 'required|integer|max:5',
            'movie_id' => 'required|exists:movies,id',
        ],[
            'required' => "Input :attribute field is required.", 
            'integer' => "Input :attribute field have to be numbers.", 
            'exists' => "Input :attribute is not found in movies table",
        ]);

        $review = Reviews::updateOrCreate(
            ['user_id' => $user->id],
            [
                'critique' => $request->input('critique'),
                'rating' => $request->input('rating'),
                'movie_id' => $request->input('movie_id'),
            ]);

            return response([
                'message' => "Review Berhasil Dibuat/Diupdate",
                'profile' => $review,
            ], 201);
    }
}
