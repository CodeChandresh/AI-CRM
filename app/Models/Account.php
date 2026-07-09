<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Account extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * The collection name for the model.
     *
     * @var string
     */
    protected $collection = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int|string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'industry',
    ];

    /**
     * Get chart data for accounts.
     *
     * @return array
     */
    public static function chartData()
    {
        return [
            ['month' => 'Jan', 'count' => 5],
            ['month' => 'Feb', 'count' => 8],
            ['month' => 'Mar', 'count' => 12],
        ];
    }
}
