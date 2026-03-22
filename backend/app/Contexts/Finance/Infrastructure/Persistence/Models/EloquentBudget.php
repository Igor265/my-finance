<?php

namespace App\Contexts\Finance\Infrastructure\Persistence\Models;

use Database\Factories\EloquentBudgetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentBudget extends Model
{
    use HasFactory;

    protected static function newFactory(): EloquentBudgetFactory
    {
        return EloquentBudgetFactory::new();
    }

    protected $table = 'budgets';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'maximum_amount',
        'currency',
        'alert_percentage',
        'start_date',
        'end_date',
    ];
}
