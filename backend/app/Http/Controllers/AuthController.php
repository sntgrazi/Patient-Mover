<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json(['error' => 'Email/Senha inválidos'], 401);
    }

    $user = auth('api')->user();

    if ($user->status === 'Inativo') {
        return response()->json(['error' => 'Conta inativa. Entre em contato com o suporte.'], 403);
    }

    return $this->respondWithToken($token, $user);
}


    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user_nome' => $user->nome,
            'user_role' => $user->role,
            'user_id' => $user->id,
            'password_reset_required' => $user->password_reset_required,
        ]);
    }

    public function resetPassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:8',
    ]);


    $user = Auth::user();

    $user->password = Hash::make($request->password);
    $user->password_reset_required = false;
    $user->save();


    return response()->json(['status' => 'Senha alterada com sucesso!'], 200);
}
}
