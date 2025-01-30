<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        /*
            kullanıcıdan bilgi alıcaz
            - name (en az 3 harf olcak)
            - email (eposta kontrolü işte @ geçecek)
            - password (en az 8 karakter olmalıdır)

            kayıt olusturulacak.

            yanıt dönülecek
        */

        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => $validate['password']
        ]);


        $user->assignRole('writer');

        return response()->json($user);
    }

    public function login(Request $request) {
        $validate = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validate['email'])
            ->first();

        if (!$user || !Hash::check($validate['password'], $user->password)) {
            return response()->json("error", 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json($token);
    }


}
