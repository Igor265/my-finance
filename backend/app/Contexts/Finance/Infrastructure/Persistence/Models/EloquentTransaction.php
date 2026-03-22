<?php

namespace App\Contexts\Finance\Infrastructure\Persistence\Models;

use Database\Factories\EloquentTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentTransaction extends Model
{
    use HasFactory;

    protected static function newFactory(): EloquentTransactionFactory
    {
        return EloquentTransactionFactory::new();
    }

    protected $table = 'transactions';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'account_id',
        'amount',
        'currency',
        'type',
        'description',
        'category_id',
        'date',
    ];
}
