<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;

class CastController extends Controller
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
        $casts = Casts::get();

        return response([
            'message' => 'Tampil Cast Berhasil',
            'data' => $casts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'age' => 'required',
            'bio' => 'required',
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        Casts::create([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'bio' =>$request->input('bio'),
        ]);

        return response([
            'message' => "Tambah Cast Berhasil",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $casts = Casts::with('listmovies')->find($id);

        if (!$casts){
            return response([
                'message' => "Detail Data Cast Tidak Ditemukan",
            ], 404);
        }

        return response([
            'message' => "Detail Data Cast",
            'data' => $casts,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'age' => 'required',
            'bio' => 'required',
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        $casts = Casts::find($id);

        if (!$casts){
            return response([
                'message' => "Detail Data Cast Tidak Ditemukan",
            ], 404);
        }
 
        $casts->name = $request->input('name');
        $casts->age = $request->input('age');
        $casts->bio = $request->input('bio');
        
        $casts->save();

        return response([
            'message' => "Update Cast $id Berhasil",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $casts = Casts::find($id);

        if (!$casts){
            return response([
                'message' => "Detail Data Cast Tidak Ditemukan",
            ], 404);
        }
 
        $casts->delete();

        return response([
            'message' => "Delete Cast $id Berhasil",
        ], 200);
    }
}
