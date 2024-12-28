<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cast_Movie;
use Illuminate\Http\Request;

class CastMovieController extends Controller
{

    public function __construct()
    {
        $this->middleware(['admin', 'auth:api'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $castmovie = Cast_Movie::get();

        return response([
            'message' => 'Tampil Cast Movie Berhasil',
            'data' => $castmovie,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
            
        ],[
            'required' => "Input :attribute field is required.",  
            'exists' => "Input :attribute is not found in genres table",   
        ]);

        $castmovies = new Cast_Movie();

        $castmovies->name = $request->input('name');
        $castmovies->cast_id = $request->input('cast_id');
        $castmovies->movie_id = $request->input('movie_id');
        

        $castmovies->save();

        return response([
            'message' => "Tambah Cast Movie Berhasil",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $castmovies = Cast_Movie::with(['movie', 'cast'])->find($id);

        if (!$castmovies){
            return response([
                'message' => "Detail Cast Movie Tidak Ditemukan",
            ], 404);
        }

        return response([
            'message' => "Detail Cast Movie",
            'data' => $castmovies,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id',
            
        ],[
            'required' => "Input :attribute field is required.",  
            'exists' => "Input :attribute is not found in genres table",   
        ]);

        $castmovies = Cast_Movie::find($id);

        if (!$castmovies){
            return response([
                'message' => "Detail Cast Movie Tidak Ditemukan",
            ], 404);
        }

        $castmovies->name = $request->input('name');
        $castmovies->cast_id = $request->input('cast_id');
        $castmovies->movie_id = $request->input('movie_id');
       
        
        $castmovies->save();

        return response([
            'message' => "Update Cast Movie Berhasil",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $castmovies = Cast_Movie::find($id);

        if (!$castmovies){
            return response([
                'message' => "Cast Movie Tidak Ditemukan",
            ], 404);
        }
 
        $castmovies->delete();

        return response([
            'message' => "Delete Cast Movie Berhasil",
        ], 200);
    }
}
