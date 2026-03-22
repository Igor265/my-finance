<?php

namespace App\Contexts\Finance\Infrastructure\Persistence\Models;

use Database\Factories\EloquentAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentAccount extends Model
{
    use HasFactory;

    protected static function newFactory(): EloquentAccountFactory
    {
        return EloquentAccountFactory::new();
    }

    protected $table = 'accounts';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'balance',
        'currency',
        'type',
    ];
}
