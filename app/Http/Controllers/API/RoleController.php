<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::get();

        return response([
            'message' => 'Tampil Role Berhasil',
            'data' => $role,
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

        $role = new Role;

        $role->name = $request->input('name');

        $role->save();

        return response([
            'message' => "Tambah Role Berhasil",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role){
            return response([
                'message' => "Detail Role Tidak Ditemukan",
            ], 404);
        }

        return response([
            'message' => "Detail Role",
            'data' => $role,
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
        
        $role = Role::find($id);


        if (!$role){
            return response([
                'message' => "Detail Role Tidak Ditemukan",
            ], 404);
        }

        $role->name = $request->input('name');
        
        $role->save();

        return response([
            'message' => "Update Role Berhasil",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role){
            return response([
                'message' => "Role Tidak Ditemukan",
            ], 404);
        }
 
        $role->delete();

        return response([
            'message' => "Delete Role Berhasil",
        ], 200);
    }
}
