<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;


class PacientesController extends Controller
{

    public function index()
    {
        return Paciente::all();
    }

    public function store(Request $request)
    {
        $paciente = Paciente::create($request->all());
        return response()->json($paciente, 201);
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado'], 404);
        }
        $paciente->update($request->all());
        return response()->json($paciente, 200);
    }

    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado'], 404);
        }
        $paciente->delete();
    }
}
