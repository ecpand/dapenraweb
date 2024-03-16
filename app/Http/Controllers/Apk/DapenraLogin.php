<?php

namespace App\Http\Controllers\Apk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Rekam;


class DapenraLogin extends Controller
{
    public function index(Request $request)
    {
        echo "berhasil";
    }

    public function login(Request $request){

        if (!Rekam::attempt($request->only('nik', 'password'))) {
              return response()->json([
                  'message' => 'Unauthorized'
              ], 401);
        }

        $user = Rekam::where('nik', $request->nik)->firstOrFail();
        //return($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
