<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscripcion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_inscripcion';
    protected $dates = ['inscripcion_fecha'];
    protected $table = 'inscripcion';
    protected $fillable = [
        'id_inscripcion',
        'numero',
        'inscripcion_codigo',
        'inscripcion_ficha_url',
        'inscripcion_fecha',
        'id_persona',
        'inscripcion_estado',
        'inscripcion_observacion',
        'envio_inscripcion',
        'verificar_expedientes',
        'es_convenio',
        'retiro_inscripcion',
        'id_pago',
        'id_programa_proceso',
        'id_inscripcion_origen',
        'inscripcion_tipo_programa',
        'es_traslado_externo',
    ];

    // Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // pago
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }

    // Programa Proceso
    public function programa_proceso()
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso', 'id_programa_proceso');
    }

    // Inscripcion Expediente
    public function expediente_inscripcion()
    {
        return $this->hasMany(ExpedienteInscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }

    // Evaluacion
    public function evaluacion()
    {
        return $this->hasOne(Evaluacion::class, 'id_inscripcion', 'id_inscripcion');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = auth()->id();
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = auth()->id();
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = auth()->id();
    //         $model->save();
    //     });
    // }
}
