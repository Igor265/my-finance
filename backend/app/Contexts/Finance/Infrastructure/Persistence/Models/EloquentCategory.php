<?php

namespace App\Contexts\Finance\Infrastructure\Persistence\Models;

use Database\Factories\EloquentCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentCategory extends Model
{
    use HasFactory;

    protected static function newFactory(): EloquentCategoryFactory
    {
        return EloquentCategoryFactory::new();
    }

    protected $table = 'categories';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'type',
    ];
}
