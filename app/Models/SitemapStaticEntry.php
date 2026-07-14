<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapStaticEntry extends Model
{
    protected $fillable = ['url', 'priority', 'change_freq', 'last_modified', 'is_active'];

    protected $casts = ['last_modified' => 'datetime', 'is_active' => 'boolean'];
}
