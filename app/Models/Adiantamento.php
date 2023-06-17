<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Adiantamento extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'colaborador',
        'email',
        'arquivo',
        'status',
        'class_status',
        'foto',
    ];

    public function getPagamentos(string|null $colaborador = null, string|null $mes = null, string|null $status = null){
        $pags = $this->where(function ($query) use ($colaborador, $mes, $status) {
            if($colaborador){
                $query->where('colaborador', $colaborador);
            }
            if($mes){
                $query->where('mes', $mes);
            }
            if($status){
                $query->where('status', $status);
            }
        })->paginate(10);
        return $pags;
    }
}
