<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TransporteRecusa;

class SolicitarTransporte extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes_transporte';

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
        return $this->belongsTo(Paciente::class,'paciente_id');
    }
    
    public function maqueiro()
    {
        return $this->belongsTo(User::class, 'maqueiro_id');
    }

    public function origem()
    {
        return $this->belongsTo(Locais::class, 'origem');
    }

    public function destino()
    {
        return $this->belongsTo(Locais::class, 'destino');
    }

    public function recusa()
    {
        return $this->hasMany(TransporteRecusa::class, 'transporte_id');
    }
}