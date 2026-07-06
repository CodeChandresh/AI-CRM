<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesForecast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'predicted_revenue',
        'actual_revenue',
        'confidence_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'predicted_revenue' => 'decimal:2',
        'actual_revenue' => 'decimal:2',
        'confidence_score' => 'decimal:2',
        'month' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the user that owns the sales forecast.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
