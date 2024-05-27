<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitarTransporte extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes_de_transporte';

    protected $fillable = [
        'paciente_id',
        'origem',
        'destino',
        'prioridade',
        'status',
        'maqueiro_id',
        'data',
        'hora',
        'descricao',
    ];
    protected $casts = [
        'data' => 'date',
        // 'hora' => 'time',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
    
    public function maqueiro()
    {
        return $this->belongsTo(User::class, 'maqueiro_id');
    }
}