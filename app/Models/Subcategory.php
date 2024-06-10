<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope());
    }
}
