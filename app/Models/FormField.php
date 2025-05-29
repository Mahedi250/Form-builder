<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
   protected $fillable = [
        'form_id', 'name', 'label', 'type', 'placeholder', 'required',
        'order_index', 'validation_rules', 'meta'
    ];

    protected $casts = [
        'validation_rules' => 'array',
        'meta' => 'array',
        'required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
