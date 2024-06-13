<?php

namespace App\Http\Controllers;

use App\Models\TransporteRecusa;
use Illuminate\Http\Request;
use App\Models\SolicitarTransporte;

class SolicitacaoTransporte extends Controller
{
    
    public function index()
    {
        $solicitacoes = SolicitarTransporte::with(['paciente', 'origem', 'destino'])->get();
        return $solicitacoes;
        
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

      public function aceitarTransporte(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }

        $solicitacao->maqueiro_id = $request->maqueiro_id; 
        $solicitacao->save();

        return response()->json(['message' => 'Transporte aceito', 'solicitacao' => $solicitacao], 200);
    }


    public function getSolicitacoesDisponiveis(Request $request, $id)
    {
        $maqueiroId = $id;
    
        $solicitacoes = SolicitarTransporte::with(['paciente', 'origem', 'destino'])
            ->whereDoesntHave('recusa', function ($query) use ($maqueiroId) {
                $query->where('maqueiro_id', $maqueiroId);
            })
            ->get();
    
        return response()->json($solicitacoes);
    }

    public function recusarTransporte(Request $request, $id, )
    {
        $solicitacao = SolicitarTransporte::findOrFail($id);

        // Registrar a recusa
        TransporteRecusa::create([
            'transporte_id' => $solicitacao->id,
            'maqueiro_id' => $request->maqueiroId,
        ]);

        // Retornar uma resposta apropriada
        return response()->json([
            'message' => 'Solicitação de transporte recusada com sucesso.',
        ]);
    }

    public function TransporteMaqueiroId()
    {
        $solicitacoes = SolicitarTransporte::select('maqueiro_id')->get();
        return response()->json($solicitacoes, 200);
    }

}
