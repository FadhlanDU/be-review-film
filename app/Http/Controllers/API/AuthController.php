<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail;
use App\Mail\GenerateEmailMail;
use App\Models\OtpCode;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['register', 'login']);
    }
    
    public function register (Request $request) {
        
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8|confirmed',
        ],[
            'required' => "Input :attribute field is required.",
            'unique' => "Input :attribute field is required.",
            'min' => "Input :attribute field is required.",
        ]);

        $user = new User;

        $roleUser = Role::where('name', 'user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegisterMail($user));

        return response([
            'message' => "Register Berhasil, Silahkan Check Email",
            'user' => $user,
        ], 200);
    }


    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required|min:8',
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid User'], 401);
        }

        $user = User::with(['profile', 'role'])->where('email', $request->input('email'))->first();

        return response([
            'message' => "Login Berhasil",
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function currentUser() {
        $user = auth()->user();

        $userdata = User::with('listreview')->find($user->id);

        return response()->json([
            'user' => $userdata

        ]);
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function update(Request $request)
    {
    
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8|confirmed',
        ],[
            'required' => "Input :attribute field is required.",
            'unique' => "Input :attribute field is required.",
            'min' => "Input :attribute field is required.",
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $roleUser = Role::where('name', 'user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        return response([
            'message' => "Update Berhasil",
            'user' => $user,
        ], 200);
 
    }


    public function generateotp(Request $request){
        $request->validate([
            'email' => 'required|email',
            
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        $user = User::where('email', $request->input('email'))->first();

        $user->generate_otp();

        Mail::to($user->email)->send(new GenerateEmailMail($user));

        return response()->json([
            "message" => "OTP Code Berhasil di Generate, Silahkan Cek Email"
        ]);

    }

    public function verifikasi(Request $request){
        $request->validate([
            'otp' => 'required|integer|min:6',
            
        ],[
            'required' => "Input :attribute field is required.",
        ]);

        $user = auth()->user();

        //otp tidak ada
        $otp_code = OtpCode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if(!$otp_code){
            return response([
                "message" => "OTP Tidak Ditemukan"
            ], 400);
        }

        //otp expired
        $now = Carbon::now();
        if($now > $otp_code->valid_until){
            return response([
                "message" => "OTP Sudah Expired, Silahkan Generate OTP Code Baru"
            ], 400);
        }

        //update user
        $user = User::find($otp_code->user_id);

        $user->email_verified_at = $now;

        $user->save();

        $otp_code->delete();

        return response([
            "message" => "Verifikasi Berhasil"
        ], 200);

    }
}