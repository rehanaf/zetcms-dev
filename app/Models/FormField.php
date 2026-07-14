<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = ['form_id', 'label', 'name', 'type', 'options', 'placeholder', 'is_required', 'order'];

    protected $casts = ['options' => 'array', 'is_required' => 'boolean'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
