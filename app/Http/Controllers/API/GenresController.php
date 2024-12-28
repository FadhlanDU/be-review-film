<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;

class GenresController extends Controller
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
        $genres = Genre::get();

        return response([
            'message' => 'Tampil Genre Berhasil',
            'data' => $genres,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        Genre::create([
            'name' => $request->input('name'),
        ]);

        return response([
            'message' => "Tambah Genre Berhasil",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genres = Genre::with(['listMovies'])->find($id);

        if (!$genres){
            return response([
                'message' => "Detail Data Genre Tidak Ditemukan",
            ], 404);
        }

        return response([
            'message' => "Detail Data Genre",
            'data' => $genres,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        $genres = Genre::find($id);

        if (!$genres){
            return response([
                'message' => "Detail Data Genre Tidak Ditemukan",
            ], 404);
        }
 
        $genres->name = $request->input('name');
        
        $genres->save();

        return response([
            'message' => "Update Genre $id Berhasil",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genres = Genre::find($id);

        if (!$genres){
            return response([
                'message' => "Detail Data Cast Tidak Ditemukan",
            ], 404);
        }
 
        $genres->delete();

        return response([
            'message' => "Delete Cast $id Berhasil",
        ], 200);
    }
}
