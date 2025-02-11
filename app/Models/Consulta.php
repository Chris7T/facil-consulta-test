<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Consulta extends Model
{
    use SoftDeletes;

    protected $fillable = ['medico_id', 'paciente_id', 'data'];

    protected $casts = [
        'data' => 'datetime:Y-m-d H:i:s'
    ];

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => date('Y-m-d H:i:s', strtotime($value)),
        );
    }
}
