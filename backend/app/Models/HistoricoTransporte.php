<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoTransporte extends Model
{
    use HasFactory;

    protected $table = 'historico_transporte';

    protected $fillable = [
        'solicitacao_id',
        'momento',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(SolicitarTransporte::class, 'solicitacao_id');
    }
}
