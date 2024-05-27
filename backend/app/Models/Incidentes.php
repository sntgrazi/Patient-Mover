<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SolicitarTransporte;
use App\Models\User;

class Incidentes extends Model
{
    use HasFactory;

    protected $table = 'incidentes';

    protected $fillable = [
        'solicitacao_transporte_id',
        'tipo',
        'descricao',
        'data_hora',
        'registrado_por'
    ];

 
    public function solicitacaoTransporte()
    {
        return $this->belongsTo(SolicitarTransporte::class, 'solicitacao_transporte_id');
    }

  
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

 
}
