<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'method', 'action'];

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order_index');
    }
}
