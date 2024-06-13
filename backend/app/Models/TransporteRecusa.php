<?php

namespace App\Models;
use App\Models\SolicitarTransporte;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransporteRecusa extends Model
{
    use HasFactory;

    protected $fillable = [
        'transporte_id',
        'maqueiro_id',
    ];

    public function solicitacao(){
        return $this->belongsTo(SolicitarTransporte::class);
    }

    public function maqueiro(){
        return $this->belongsTo(User::class);
    }
}
