<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'verfiedAccount']);
    }

    public function storeupdate(Request $request) {
        $user = auth()->user();

        $request->validate([
            'age' => 'required|integer',
            'biodata' => 'required',
            'address' => 'required',
        ],[
            'required' => "Input :attribute field is required.", 
            'integer' => "Input :attribute field have to be numbers.", 
        ]);

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'age' => $request->input('age'),
                'biodata' => $request->input('biodata'),
                'address' => $request->input('address'),
            ]);

            return response([
                'message' => "Profile Berhasil Dibuat/Diupdate",
                'profile' => $profile,
            ], 201);
    }
}
