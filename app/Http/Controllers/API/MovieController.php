<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
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
        $movies = Movie::get();

        return response([
            'message' => 'Tampil Movie Berhasil',
            'data' => $movies,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'summary' => 'required',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required|max:4',
        ],[
            'required' => "Input :attribute field is required.",
            'mimes' => "Input :attribute field is have to be jpeg, png, jpg, gif.",
            'max' => "Input :attribute field is have to max of 2048",
            'image' => "Input :attribute field is have to be image",   
            'exists' => "Input :attribute is not found in genres table",   
        ]);

        $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
            'folder' => 'image',
        ])->getSecurePath();

        $movies = new Movie;

        $movies->title = $request->input('title');
        $movies->summary = $request->input('summary');
        $movies->poster = $uploadedFileUrl;
        $movies->genre_id = $request->input('genre_id');
        $movies->year = $request->input('year');

        $movies->save();

        return response([
            'message' => "Tambah Movie Berhasil",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $movies = Movie::with(['genre', 'listcasts', 'listreview'])->find($id);

        if (!$movies){
            return response([
                'message' => "Detail Data Movie Tidak Ditemukan",
            ], 404);
        }

        return response([
            'message' => "Detail Data Movie",
            'data' => $movies,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'summary' => 'required',
            'poster' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'genre_id' => 'required|exists:genres,id',
            'year' => 'required|max:4',
        ],[
            'required' => "Input :attribute field is required.",
            'mimes' => "Input :attribute field is have to be jpeg, png, jpg, gif.",
            'max' => "Input :attribute field is have to max of 2048",
            'image' => "Input :attribute field is have to be image",   
            'exists' => "Input :attribute is not found in genres table",   
        ]);
        
        $movies = Movie::find($id);
        
        if($request->hasFile('poster')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('poster')->getRealPath(), [
                'folder' => 'image',
            ])->getSecurePath();
            $movies->poster = $uploadedFileUrl;
        }


        if (!$movies){
            return response([
                'message' => "Detail Data Movie Tidak Ditemukan",
            ], 404);
        }

        $movies->title = $request->input('title');
        $movies->summary = $request->input('summary');
        $movies->genre_id = $request->input('genre_id');
        $movies->year = $request->input('year');
        
        $movies->save();

        return response([
            'message' => "Update Movie Berhasil",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movies = Movie::find($id);

        if (!$movies){
            return response([
                'message' => "Movie Tidak Ditemukan",
            ], 404);
        }
 
        $movies->delete();

        return response([
            'message' => "Delete Movie Berhasil",
        ], 200);
    }
}
