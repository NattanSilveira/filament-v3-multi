<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'content',
        'class',
        'method',
        'line',
        'file',
        'trace',
        'user_id',
    ];

    protected $casts = [
        'content' => 'array',
        'trace' => 'array',
    ];
}
