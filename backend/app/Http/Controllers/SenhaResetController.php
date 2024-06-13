<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SenhaTemporaria;


class SenhaResetController extends Controller
{
    public function senhaTemporariaReset(Request $request){

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(['message' => 'Email não encontrado'], 404);
        }

        $temporaryPassword = Str::random(8);
        $user->password = Hash::make($temporaryPassword);
        $user->password_reset_required = true;
        $user->save();

        Mail::to($user->email)->send(new SenhaTemporaria($temporaryPassword));

        return response()->json(['message' => 'Senha temporária enviada para o email cadastrado'], 200);

    }

}
