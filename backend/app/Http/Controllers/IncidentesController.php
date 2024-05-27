<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidentes;

class IncidentesController extends Controller
{
    
    public function index()
    {
        return Incidentes::all();
    }

    public function store(Request $request)
    {   
        $incidentes = Incidentes::create($request->all());
        return response()->json($incidentes, 201);
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
