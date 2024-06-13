<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SenhaTemporaria;
use Illuminate\Support\Str;

class UserController extends Controller
{
    
   public function index()
    {
        return User::all();
    }

    public function storeAdmin (Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['role'] = 'admin';

        $user = User::create($validatedData);
        return response()->json($user, 201);
    }

    public function storeMaqueiro(Request $request){
        $validatedData = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'required|email|unique:usuarios',
        ]);

        $temporaryPassword = Str::random(8);
        $validatedData['password'] = Hash::make($temporaryPassword);
        $validatedData['password_reset_required'] = true;
        $validatedData['role'] = 'maqueiro';

        $user = User::create($validatedData);

        Mail::to($user->email)->send(new SenhaTemporaria($temporaryPassword));

        return response()->json($user, 201);

    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        
        $validatedData = $request->validate([
            'nome' => 'required|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $user->id,
        ]);
    
        
        $user->update($validatedData);
    
        return response()->json($user, 200);
    } 

    public function deactivate($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        
        
        $user->status = 'Inativo';
        $user->save();
    
        return response()->json(['message' => 'Usuário desativado com sucesso'], 200);
    }

    public function activate($id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    $user->status = 'Ativo';
    $user->save();

    return response()->json(['message' => 'Usuário ativado com sucesso'], 200);
}
    
}
