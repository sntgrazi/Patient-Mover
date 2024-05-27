<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitarTransporte;

class SolicitacaoTransporte extends Controller
{
    
    public function index()
    {
        return SolicitarTransporte::all();
    }

    public function store(Request $request)
    {
        $solicitacao = SolicitarTransporte::create($request->all());
        return response()->json($solicitacao, 201);
    }

    public function update(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }
        $solicitacao->update($request->all());
        return response()->json($solicitacao, 200);
    }

    public function destroy($id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }
        $solicitacao->delete();
    }

}
