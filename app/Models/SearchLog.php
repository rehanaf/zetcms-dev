<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['query', 'results_count', 'user_id', 'session_id'];
}
