<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SolicitarTransporte;
use App\Models\User;

class Incidentes extends Model
{
    use HasFactory;

    

 
    public function solicitacaoTransporte()
    {
        return $this->belongsTo(SolicitarTransporte::class, 'solicitacao_transporte_id')->with('paciente');
    }

  
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }


}
