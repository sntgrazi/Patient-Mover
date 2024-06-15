<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidentes;

class IncidentesController extends Controller
{
    
    public function index($id)
    {
        $incidentes = Incidentes::where('solicitacao_transporte_id', $id)->with('registradoPor')->get();
        return response()->json($incidentes);
    }

    public function indexAll(){
        return response()->json(Incidentes::all());
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
        ]);

        $incidente = new Incidentes();
        $incidente->solicitacao_transporte_id = $id;
        $incidente->descricao = $request->descricao;
        $incidente->registrado_por = $request->maqueiroId;
        $incidente->save();

        return response()->json(['message' => 'Incidente relatado com sucesso', 'incidente' => $incidente], 201);
    }
    public function show($id)
    {
        return Incidentes::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $incidentes = Incidentes::find($id);
        if (!$incidentes) {
            return response()->json(['message' => 'Incidente não encontrado'], 404);
        }
        $incidentes->update($request->all());
        return response()->json($incidentes, 200);
    }

    public function destroy($id)
    {
        $incidente = Incidentes::find($id);
        if (!$incidente) {
            return response()->json(['message' => 'Incidente não encontrado'], 404);
        }
        $incidente->delete();
    }


}
