<?php

namespace App\Contexts\Finance\Infrastructure\Persistence\Models;

use Database\Factories\EloquentFinancialGoalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentFinancialGoal extends Model
{
    use HasFactory;

    protected static function newFactory(): EloquentFinancialGoalFactory
    {
        return EloquentFinancialGoalFactory::new();
    }

    protected $table = 'financial_goals';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'currency',
        'deadline',
    ];
}
