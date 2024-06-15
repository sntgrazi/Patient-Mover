<?php

namespace App\Http\Controllers;

use App\Models\TransporteRecusa;
use Illuminate\Http\Request;
use App\Models\SolicitarTransporte;
use App\Models\HistoricoTransporte;

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
        $this->registrarHistorico($solicitacao->id, 'Transporte Pendente');
        return response()->json($solicitacao, 201);
    }

    public function update(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }
        $solicitacao->update($request->all());
        $this->registrarHistorico($id, 'Solicitação atualizada');
        return response()->json($solicitacao, 200);
    }

    public function destroy($id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }
        $solicitacao->delete();
        $this->registrarHistorico($id, 'Solicitação excluída');
    }

    public function aceitarTransporte(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::find($id);
        if (!$solicitacao) {
            return response()->json(['message' => 'Solicitação não encontrada'], 404);
        }

        $solicitacao->maqueiro_id = $request->maqueiro_id; 
        $solicitacao->status = 'Pendente';
        $solicitacao->save();

        $this->registrarHistorico($id, 'Transporte aceito');

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

    public function recusarTransporte(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::findOrFail($id);

        
        TransporteRecusa::create([
            'transporte_id' => $solicitacao->id,
            'maqueiro_id' => $request->maqueiroId,
        ]);


       
        return response()->json([
            'message' => 'Solicitação de transporte recusada com sucesso.',
        ]);
    }

    public function TransporteMaqueiroId()
    {
        $solicitacoes = SolicitarTransporte::select('maqueiro_id')->get();
        return response()->json($solicitacoes, 200);
    }

    public function iniciarTransporte(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::findOrFail($id);
        $solicitacao->status = 'em_transporte';
        $solicitacao->save();

        $this->registrarHistorico($id, 'Transporte iniciado');

        return response()->json([
            'message' => 'Transporte iniciado com sucesso.',
            'solicitacao' => $solicitacao,
        ]);
    }

    public function concluirTransporte(Request $request, $id)
    {
        $solicitacao = SolicitarTransporte::findOrFail($id);
        $solicitacao->status = 'concluido';
        $solicitacao->save();

        $this->registrarHistorico($id, 'Transporte concluído');

        return response()->json([
            'message' => 'Transporte concluído com sucesso.',
            'solicitacao' => $solicitacao,
        ]);
    }

    private function registrarHistorico($solicitacaoId, $momento)
    {
        HistoricoTransporte::create([
            'solicitacao_id' => $solicitacaoId,
            'momento' => $momento,
        ]);
    }

    public function historicoTransporte($id)
    {
        $historico = HistoricoTransporte::where('solicitacao_id', $id)->get();
        return response()->json($historico);
    }
}
